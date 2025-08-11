<?php

namespace App\Http\Controllers;

use App\Enums\LocationStatusEnum;
use App\Enums\ShipmentStatusEnum;
use App\Enums\ShipmentTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\RmaTechnician;
use App\Models\ServiceItem;
use App\Models\ServiceProcess;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ServiceProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // hanya RMA yang bisa melihat ini 
        if (!Auth::user()->isRma()) {
            abort(403,"Akses Ditolak");
        }

        // ambil service item yang lokasinya sudah di RMA
        $serviceItems = ServiceItem::with(['customer', 'serviceProcesses', 'creator', 'shipments' => function ($query) {
            $query->where('shipment_type', ShipmentTypeEnum::ToRMA)
                  ->orderByDesc('created_at');
            }])
            ->where('location_status', LocationStatusEnum::AtRMA)
            ->get();

        // Filter di Collection
        $filterServiceItem = $serviceItems->filter(function ($item) {
            // filter yang belum selesai atau batal secara proses
            $latestProcess = $item->serviceProcesses->sortByDesc('created_at')->first();
            $finalProcessStatuses = ['Selesai', 'Tidak bisa diperbaiki'];
            $isNotFinished = !$latestProcess || !in_array($latestProcess->process_status, $finalProcessStatuses);

            // Pastikan ada shipment terakhir dari admin dan statusnya 'Diterima'
            $latestInboundShipment = $item->shipments->first(); // Karena sudah kita limit

            $isReceivedFromAdmin = $latestInboundShipment && $latestInboundShipment->shipment_type === ShipmentTypeEnum::ToRMA && $latestInboundShipment->status === ShipmentStatusEnum::Diterima;
            
            // return ((!$latestProcess || $latestProcess->process_status !== 'Selesai') && (!$latestProcess || $latestProcess->process_status !== 'Tidak bisa diperbaiki'));
            // return !$latestProcess || !in_array($latestProcess->process_status, $finalProcessStatuses);

            return $isNotFinished && $isReceivedFromAdmin;
        });

        // Manual Pagination 
        $currentPage = LengthAwarePaginator::resolveCurrentPage(); // Mengambil nomor halaman saat ini dari query string (contoh: ?page=2). Misal URL: example.com/barang?page=3, maka resolveCurrentPage() akan mengembalikan 3.
        $perPage = 10; // Jumlah data yang ditampilkan dalam satu page
        $currentItem = $filterServiceItem->slice(($currentPage - 1) * $perPage, $perPage)->values(); // mengambil jumlah item yang ditampilkan di halaman itu
        $paginationItems = new LengthAwarePaginator(
            $currentItem, // item yang ditampilkan di halaman ini
            $filterServiceItem->count(), // totak seluruh item setelah di filter
            $perPage, // jumlah item per halaman
            $currentPage // halaman saat ini
        );

        $paginationItems->withPath(request()->url()); // agar pagination link tetap benar
        
        return view('service_processes.index', ['serviceItems' => $paginationItems]);
    }

    /**
     * Menampilkan form untuk mengerjakan/melanjutkan proses servis.
     */
    public function workOn(ServiceItem $serviceItem)
    {
        if (!Auth::user()->isRma()) {
            abort(403,'Akses Ditolak');
        }

        // Validasi untuk memastikan item memang siap dikerjakan 
        if ($serviceItem->location_status !== LocationStatusEnum::AtRMA) {
            return redirect()->route('service_items.index')->with('error', 'Barang ini tidak berada di RMA atau belum siap dikerjakan');
        }

        // Load proses terakhir untuk service item ini (jika ada)
        $latestProcess = $serviceItem->serviceProcesses->sortByDesc('created_at')->first();

        // status yang tersedia untuk dropdown
        $statuses = ['Pending', 'Diagnosa', 'Proses Pengerjaan', 'Menunggu Sparepart', 'Selesai', 'Tidak bisa diperbaiki'];

        // RMA Technicians
        $rmaTechnicians = RmaTechnician::all();

        return view('service_processes.work_on', compact('serviceItem', 'latestProcess', 'statuses', 'rmaTechnicians'));
    }

    /**
     * Menyimpan proses servis baru atau memperbarui yang sudah ada.
     */
    public function storeWork(Request $request, ServiceItem $serviceItem)
    {
        // hanya RMA yang dapat melakukan ini
        if (!Auth::user()->isRma()) {
            abort(403, 'Akses Ditolak');
        }

        $request->validate([
            'damage_analysis_detail' => 'nullable|string',
            'solution' => 'nullable|string',
            'process_status' => 'required|string|in:Pending,Diagnosa,Proses Pengerjaan,Menunggu Sparepart,Selesai,Tidak bisa diperbaiki',
            'keterangan' => 'nullable|string',
            'rma_technician_id' => 'required|exists:rma_technicians,id', // validasi teknisi
        ]);

        // pastikan barang memang ada di RMA sebelum dikerjakan
        if ($serviceItem->location_status !== LocationStatusEnum::AtRMA) {
            return redirect()->back()->with('error', 'Barang belum diterima di RMA atau sidah diKirim kembali');
        }
        
        // cek apakah ada process terakhir untuk service item ini
        $latestProcess = $serviceItem->serviceProcesses()->latest()->first();
        // tentukan status-status yang dianggap final dan tidak boleh diupdate
        $finalStatuses = ['Selesai', 'Tidak bisa diperbaiki'];

        if ($latestProcess && !in_array($latestProcess->process_status, $finalStatuses)) {
            $latestProcess->update([
                'damage_analysis_detail' => $request->damage_analysis_detail,
                'solution' => $request->solution,
                'process_status' => $request->process_status,
                'keterangan' => $request->keterangan,
                'handle_by_user_id' => Auth::id(), // Simpan ID user yang sedang login saat ini dan mengerjakan update antrian service
            ]);
            $message = 'Process service berhasil diperbarui';
        } else {
            /**
             * jika tidak ada process sama sekali atau process sudah final
             * maka buat entri proses service baru
             */
            ServiceProcess::create([
                'service_item_id' => $serviceItem->id,
                'damage_analysis_detail' => $request->damage_analysis_detail,
                'solution' => $request->solution,
                'process_status' => $request->process_status,
                'keterangan' => $request->keterangan,
                'handle_by_user_id' => Auth::id(), // Simpan ID user yang sedang login saat mulai mengerjakan
            ]);
            $message = 'Proses service berhasil ditambahkan';
        }

        // Update/attach teknisi di pivot
        $serviceItem->rmaTechnicians()->sync([$request->rma_technician_id]);

        return redirect()->route('service_processes.index')->with('success', $message);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua service untuk dropdown
        $serviceItems = ServiceItem::with('customer')->get();
        $statuses = ['Pending', 'Diangnosis', 'Proses pengerjaan', 'Menuggu Sparepart', 'Selesai', 'Tidak bisa diperbaiki'];
        return view('service_processes.create', compact('serviceItems', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_item_id' => 'required|exists:service_items,id',
            'damage_analysis_detail' => 'nullable|string',
            'solution' => 'nullable|string',
            'process_status' => 'required|string|in:Pending,Diangnosis,Proses pengerjaan,Menuggu Sparepart,Selesai,Tidak bisa diperbaiki',
            'keterangan' => 'nullable|string',
        ]);

        ServiceProcess::create($request->all());

        return redirect()->route('service_processes.index')->with('success', 'Proses servis berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceProcess $serviceProcess)
    {
        // Load relasi serviceItem dan customer
        $serviceProcess->load('serviceItem.customer', 'handler'); // load handler juga
        return view('service_processes.show', compact('serviceProcess'));
    }

    // metode indexAll untuk developer/superadmin
    public function indexActivityServiceProcesses() 
    {
        $serviceItems = ServiceItem::all();
        return view('service_processes.activity_service_processes_index', compact('serviceItems'));
    }

    public function changeWorkOn(ServiceItem $serviceItem)
    {
        // Load proses terakhir untuk service item ini
        $latestProcess = $serviceItem->serviceProcesses->sortByDesc('created_at')->first();

        // status yang tersedia untuk dropdown
        $statuses = ['Pending', 'Diagnosa', 'Proses Pengerjaan', 'Menunggu Sparepart', 'Selesai', 'Tidak bisa diperbaiki'];
        
        // RMA Technicians
        $rmaTechnicians = RmaTechnician::all();

        return view('service_processes.change_work_on', compact('serviceItem', 'latestProcess', 'statuses', 'rmaTechnicians'));
    }

    public function storeChangeWorkOn(Request $request, ServiceItem $serviceItem)
    {
        $request->validate([
            'damage_analysis_detail' => 'nullable|string',
            'solution' => 'nullable|string',
            'process_status' => 'required|string|in:Pending,Diagnosa,Proses Pengerjaan,Menunggu Sparepart,Selesai,Tidak bisa diperbaiki',
            'keterangan' => 'nullable|string',
            'rma_technician_id' => 'required|exists:rma_technicians,id', // validasi teknisi
        ]);

        // cek apakah ada process terakhir untuk service item ini
        $latestProcess = $serviceItem->serviceProcesses()->latest()->first();
        // tentukan status-status yang dianggap final dan tidak boleh diupdate
        // $finalStatuses = ['Selesai', 'Tidak bisa diperbaiki'];

        if ($latestProcess && $latestProcess->process_status) {
            $latestProcess->update([
                'damage_analysis_detail' => $request->damage_analysis_detail,
                'solution' => $request->solution,
                'process_status' => $request->process_status,
                'keterangan' => $request->keterangan,
                'handle_by_user_id' => Auth::id(), // Simpan ID user yang sedang login saat ini dan mengerjakan update antrian service
            ]);
            $message = 'Process service berhasil diperbarui';
        } else {
            /**
             * jika tidak ada process sama sekali atau process sudah final
             * maka buat entri proses service baru
             */
            ServiceProcess::create([
                'service_item_id' => $serviceItem->id,
                'damage_analysis_detail' => $request->damage_analysis_detail,
                'solution' => $request->solution,
                'process_status' => $request->process_status,
                'keterangan' => $request->keterangan,
                'handle_by_user_id' => Auth::id(), // Simpan ID user yang sedang login saat mulai mengerjakan
            ]);
            $message = 'Proses service berhasil ditambahkan';
        }

        // Update/attach teknisi di pivot
        $serviceItem->rmaTechnicians()->sync([$request->rma_technician_id]);

        return redirect()->route('activity.service_processes.index')->with('success', $message);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceProcess $serviceProcess)
    {
        $serviceItems = ServiceItem::with('customer')->get();
        $statuses = ['Pending', 'Diangnosis', 'Proses pengerjaan', 'Menuggu Sparepart', 'Selesai', 'Tidak bisa diperbaiki'];
        return view('service_processes.edit', compact('serviceProcess', 'serviceItems', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceProcess $serviceProcess)
    {
        $request->validate([
            'service_item_id' => 'required|exists:service_items,id',
            'damage_analisys_detail' => 'nullable|string',
            'solution' => 'nullable|string',
            'process_status' => 'required|string|in:Pending,Diangnosis,Proses pengerjaan,Menuggu Sparepart,Selesai,Tidak bisa diperbaiki',
            'keterangan' => 'nullable|string',
        ]);

        $serviceProcess->update($request->all());
        return redirect()->route('service_processes.index')->with('success', 'Proses service berhasil di update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceProcess $serviceProcess)
    {
        $serviceProcess->delete();
        return redirect()->route('service_processes.index')->with('success', 'Proses servis berhasil dihapus!');
    }
}

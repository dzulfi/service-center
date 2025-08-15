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
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

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

    /**
     * Aktivitas RMA 
     */
    public function indexActivityServiceProcesses() 
    {
        $serviceItems = ServiceItem::all();
        return view('service_processes.activity_service_processes_index'
            , compact('serviceItems')
        );
    }
    
    // Get Data Aktivitas RMA
    public function getDataActivityRma(Request $request)
    {
        $query = ServiceItem::with([
            'itemType',
            'merk',
            'serviceProcesses',
            'stockSpareparts',
            'rmaTechnicians',
        ]);

        return DataTables::of($query)
            ->addColumn('start_process', function ($row) {
                return $row->mulai_dikerjakan ? $row->mulai_dikerjakan->format('d M Y H:i') : '-';
            })
            ->addColumn('finish_process', function ($row) {
                return $row->selesai_dikerjakan ? $row->selesai_dikerjakan->format('d M Y H:i') : '-';
            })
            ->addColumn('type', function ($row) {
                return $row->itemType ? $row->itemType->type_name : '-';
            })
            ->addColumn('merk', function ($row) {
                return $row->merk ? $row->merk->merk_name : '-';
            })
            ->addColumn('damage_analysis_detail', function ($row) {
                if ($row->serviceProcesses->isNotEmpty()) {
                    foreach($row->serviceProcesses as $service) {
                        return $service ? $service->damage_analysis_detail : '-';
                    }
                }
                return '-';
            })
            ->addColumn('solution', function ($row) {
                if ($row->serviceProcesses->isNotEmpty()) {
                    foreach($row->serviceProcesses as $service) {
                        return $service ? $service->solution : '-';
                    }
                }
                return '-';
            })
            ->addColumn('sparepart', function ($row) {
                if ($row->stockSpareparts->isEmpty()) {
                    return '<div style="color: rgb(255, 93, 93); font-weight: bold;">Tidak memakai sparepart</div>';
                }

                $items = [];
                foreach ($row->stockSpareparts->groupBy('sparepart_id') as $sparepartId => $stocks) {
                    $sparepartName = $stocks->first()->sparepart->name ?? 'Nama tidak ditemukan';
                    $currentStock = $row->getCurrentStockForSparepart($sparepartId);

                    if ($currentStock != 0) {
                        $items[] = '<li>' . e($sparepartName) . ' (stock: ' . $currentStock . ')</li>';
                    }
                }

                if (empty($items)) {
                    return '<div style="color: rgb(255, 93, 93); font-weight: bold;">Tidak memakai sparepart</div>';
                }

                return '<ul class="list-disc">' . implode('', $items) . '</ul>';
            })
            ->addColumn('status', function ($row) {
                $latestProcess = $row->serviceProcesses->sortByDesc('created_at')->first();
                $status = $latestProcess ? $latestProcess->process_status : 'Pending';
                $statusSlug = Str::slug($status);

                return '<span class="status-badge status-' . $statusSlug . '">' . e($status) . '</span>';
            })
            ->addColumn('technician', function ($row) {
                if ($row->rmaTechnicians->isNotEmpty()) {
                    return $row->rmaTechnicians->pluck('name')->join(', ');
                }

                return '
                    <div class="no-rma">
                        Belum ada
                    </div>
                ';
            })
            ->addColumn('action_process', function ($row) {
                return '
                    <div class="actions">
                        <a href="' . route('activity.service_processes.change', $row->id) . '" class="work-button">Perubahan</a>
                    </div>
                ';
            })
            ->addColumn('action_sparepart', function ($row) {
                return '
                    <div class="actions">
                        <a href="' . route('stock_out.index', $row->id) . '" class="stock-out">Gunakan</a>
                        <a href="' . route('stock_return.create', $row->id) . '" class="stock-return">Kembalikan</a>
                    </div>
                ';
            })
            
            // === FILTER KOLOM RELASI TANPA MENGHAPUS DATA NULL ===
            ->filterColumn('type', function($query, $keyword) {
                $query->whereHas('itemType', function($q) use ($keyword) {
                    $q->where('type_name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('merk', function($query, $keyword) {
                $query->whereHas('merk', function ($q) use ($keyword) {
                    $q->where('merk_name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('damage_analysis_detail', function ($query, $keyword) {
                $query->whereHas('serviceProcesses', function ($q) use ($keyword) {
                    $q->where('damage_analysis_detail', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('solution', function ($query, $keyword) {
                $query->whereHas('serviceProcesses', function ($q) use ($keyword) {
                    $q->where('solution', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('sparepart', function ($query, $keyword) {
                $query->whereHas('stockSpareparts.sparepart', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('technician', function ($query, $keyword) {
                $query->whereHas('rmaTechnicians', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filter(function ($query) use ($request) {
                // Filter berdasarkan Teknisi RMA
                if ($request->handler && $request->handler !== 'all') {
                    if ($request->handler === 'belum-ditangani') {
                        $query->whereDoesntHave('rmaTechnicians');
                    } else {
                        $query->whereHas('rmaTechnicians', function ($q) use ($request) {
                            $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower(str_replace('-', ' ', $request->handler)) . '%']);
                        });
                    }
                }

                // Filter berdasarkan status proses
                if ($request->status_filter) {
                    if ($request->status_filter === 'selesai') {
                        $query->whereHas('serviceProcesses', function ($q) {
                            $q->where('process_status', 'Selesai');
                        });
                    } elseif ($request->status_filter === 'tidak-bisa-diperbaiki') {
                        $query->whereHas('serviceProcesses', function ($q) {
                            $q->whereIn('process_status', ['Batal', 'Tidak bisa diperbaiki']);
                        });
                    } elseif ($request->status_filter === 'proses-pengerjaan') {
                        $query->where(function ($q) {
                            $q->whereDoesntHave('serviceProcesses') // belum ada proses sama seklai
                              ->orWhereHas('serviceProcesses', function ($sq) {
                                $sq->whereNotIn('process_status', ['Selesai', 'Batal', "Tidak bisa diperbaiki"]);
                              });
                        });
                    }
                }

                // Filter range Mulai
                if ($request->start_mulai && $request->end_mulai) {
                    $query->whereHas('serviceProcesses', function ($q) use ($request) {
                        $q->whereDate('created_at', '>=', $request->start_mulai)
                        ->whereDate('created_at', '<=', $request->end_mulai);
                    });
                }
                
                // filter range selesai
                if ($request->start_selesai && $request->end_selesai) {
                    $query->whereHas('serviceProcesses', function ($q) use ($request) {
                        $q->where('process_status', 'Selesai')
                        ->whereDate('updated_at', '>=', $request->start_selesai)
                        ->whereDate('updated_at', '<=', $request->end_selesai);
                    });
                }
            }, true)
            ->rawColumns(['technician', 'action_process', 'action_sparepart', 'damage', 'solution', 'sparepart', 'status', 'damage_analysis_detail'])
            ->addIndexColumn()
            ->make(true);
    }
}

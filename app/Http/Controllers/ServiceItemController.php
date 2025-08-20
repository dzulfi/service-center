<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Merk;
use App\Models\Customer;
use App\Models\ItemType;
use App\Models\ServiceItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\ShipmentTypeEnum;
use App\Enums\LocationStatusEnum;
use App\Enums\ShipmentStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ServiceItemController extends Controller
{
    /**
     * Display a listing of the resource.
     * menampilkan daftar barang service yang hanya dibuat oleh admin cabang tersebut
     */
    public function index()
    {
        return view('service_items.index');
    }

    /**
     * Metode untuk menampilkan daftar semua barang service untuk dilihat Role Developer dan Superadmin
     */
    public function indexAllServiceItems()
    {
        return view('service_items.index_all');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // hanya admin yang bisa membuat service ini
        if (!Auth::user()->isAdmin()) {
            abort(403,'Akses Ditolak');
        }

        $customers = Customer::all(); // mengambil semua Mitra Bisnis untuk di dropdown
        $itemTypes = ItemType::all();
        $merks = Merk::all();
        return view('service_items.create', compact('customers', 'itemTypes', 'merks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // hanya admin yang bisa membuat service item
        if (!Auth::user()->isAdmin()){
            abort(403,'Akses Ditolak');
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'analisa_kerusakan' => 'required|string',
            // 'item_type_id' => 'required|exists:item_types,id',
            'item_type_id'=> 'required|string|max:255',
            // 'merk_id'=> 'required|exists:merks,id',
            'merk_id' => 'required|string|max:255',
        ]);

        // Logika pembuatan generate kode service
        $user = Auth::user();
        // mengabil kode branch office
        $branchOfficeCode = $user->branchOffice->code;

        // konversi kode untuk date
        $datePart = Carbon::now()->format('ymd');

        // Cari ServiceItem dengan kode TERTINGGI secara keseluruhan (global)
        // Ini akan digunakan untuk mengekstrak nomor urut tertinggi
        // Asumsi: Kode selalu dalam format fixed-length [BranchCode][YYMMDD][NomorUrut]
        // $latestServiceItem = ServiceItem::orderByDesc('code')->first();
        $latestServiceItem = ServiceItem::orderByDesc('id')->first();

        $sequentialNumber = 1; // Nomor urut default jika belum ada item hari ini dari cabang ini
        if ($latestServiceItem) {
            // Ambil 6 digit terakhir dari kode urut, konversi ke integer, lalu tambahkan 1
            $lastCode = $latestServiceItem->code;
            $latestSequentialNumber = (int) substr($lastCode, -5);
            $sequentialNumber = $latestSequentialNumber + 1;
        }

        // Format nomor urut dengan 6 digit (000001, 000010)
        $formattedSequentialNumber = str_pad($sequentialNumber, 5, '0', STR_PAD_LEFT);

        // Gabungkan semua bagian untuk mendapatkan kode final
        $generatedCode = $branchOfficeCode . $datePart . $formattedSequentialNumber;

        while (ServiceItem::where('code', $generatedCode)->exists()) {
            $sequentialNumber++;
            $formattedSequentialNumber = str_pad($sequentialNumber, 5, '0', STR_PAD_LEFT);
            $generatedCode = $branchOfficeCode . $datePart . $formattedSequentialNumber;
        }

        // DYNAMIC OPTIONS ITEM TYPES
        // Fitur checking item type tersedia atau tidak ada jika tidak ada maka dibuatkan
        $itemTypeInput = $request->input('item_type_id'); // request input dari user 
        // Cek apakah input berupa ID numerik (dari dropdown)
        if (is_numeric($itemTypeInput)) {
            $itemTypeId = $itemTypeInput; // cek jika ada dan berupa ID maka tidka perlu buat baru
        } else {
            // jika bukan angka atau ID, berarti ini nama baru - cari atau buat
            $itemType = ItemType::firstOrCreate([
                'type_name' => $itemTypeInput
            ]);
            $itemTypeId = $itemType->id;
        }

        // DYNAMIC OPTIONS MERKS
        // Fitur checking item type tersedia atau tidak ada jika tidak ada maka dibuatkan
        $merkInput = $request->input('merk_id'); // request input dari user 
        // Cek apakah input berupa ID numerik (dari dropdown)
        if (is_numeric($merkInput)) {
            $merkId = $merkInput; // cek jika ada dan berupa ID maka tidka perlu buat baru
        } else {
            // jika bukan angka atau ID, berarti ini nama baru - cari atau buat
            $merk = Merk::firstOrCreate([
                'merk_name' => $merkInput
            ]);
            $merkId = $merk->id;
        }

        $serviceItem = ServiceItem::create([
            'customer_id' => $request->customer_id,
            'name' => $request->name,
            'serial_number' => $request->serial_number,
            'code' => $generatedCode, // Hasil penggabungan kode.
            'analisa_kerusakan' => $request->analisa_kerusakan,
            'created_by_user_id' => Auth::id(), // Simpan ID user yang sedang login
            'location_status' => LocationStatusEnum::AtBranch,
            // 'item_type_id' => $request->item_type_id,
            'item_type_id' => $itemTypeId,
            'merk_id' => $merkId,
        ]);

        return redirect()->route('service_items.index')->with('success', 'Barang servis berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceItem $serviceItem)
    {
        return view('service_items.show', compact('serviceItem'));
    }

    /**
     * Detail aktifitas barang service per id (role: developer, superadmin)
     */
    public function showDetailActivityServiceItem(ServiceItem $serviceItem)
    {
        return view('service_items.detail_activity_service_item', compact('serviceItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceItem $serviceItem)
    {
        $customers = Customer::all();
        // $itemTypes = ItemType::all();
        $merks = Merk::all();

        $serviceItem->load('itemType');

        return view('service_items.edit', compact(
            'serviceItem', 
            'customers', 
            // 'itemTypes', 
            // 'merks'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceItem $serviceItem)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'analisa_kerusakan' => 'nullable|string',
            'item_type_id' => 'required|string|max:255',
            'merk_id'=> 'required|string|max:255',
        ]);

        // $serviceItem->update($request->all());

        // DYNAMIC OPTION ITEM TYPES
        // Fitur checking item type tersedia atau tidak ada jika tidak ada maka akan dibuatkan data item type baru
        $itemTypeInput = $request->input('item_type_id');
        // Cek apakah input berupa ID numerik (dropdown)
        if (is_numeric($itemTypeInput)) {
            $itemTypeId = $itemTypeInput; // cek jika ada dan berupa ID maka tidak perlu buat baru
        } else {
            // jika bukan angka atau ID, berarti ini nama baru - cari atau buat
            $itemType = ItemType::firstOrCreate([
                'type_name' => $itemTypeInput,
            ]);
            $itemTypeId = $itemType->id;
        }

        // DYNAMIC OPTION MERKS
        // Fitur checking item type tersedia atau tidak ada jika tidak ada maka akan dibuatkan data merk baru
        $merkInput = $request->input('merk_id');
        // Cek apakah input berupa ID numerik (dropdown)
        if (is_numeric($merkInput)) {
            $merkId = $merkInput; // cek jika ada dan berupa ID maka tidak perlu buat baru
        } else {
            // jika bukan angka atau ID, berarti ini nama baru - cari atau buat
            $merk = Merk::firstOrCreate([
                'merk_name' => $merkInput,
            ]);
            $merkId = $merk->id;
        }

        $serviceItem->update([
            'customer_id' => $request->customer_id,
            'name'=> $request->name,
            'serial_number' => $request->serial_number,
            'analisa_kerusakan' => $request->analisa_kerusakan,
            'item_type_id' => $itemTypeId,
            'merk_id' => $merkId,
        ]);

        return redirect()->route('service_items.index')->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceItem $serviceItem)
    {
        $serviceItem->delete();
        return redirect()->route('service_items.index')->with('success', 'Barang service berhasil dihapus');
    }

    /**
     * Get Data Activity Service Items
     */
    public function getDataServiceItemActivity(Request $request)
    {
        // $query = ServiceItem::with(['serviceProcesses'])
        //     ->select(
        //         'service_items.*',
        //         'customers.name as customer_name',
        //         'item_types.type_name as type',
        //         'merks.merk_name as merk',
        //         DB::raw("CONCAT(users.name, ' (', IFNULL(branch_offices.name, '(Tidak Ditemukan)'), ')') as admin")
        //     )
        //     ->leftJoin('customers', 'customers.id', '=', 'service_items.customer_id')
        //     ->leftJoin('item_types', 'item_types.id', '=', 'service_items.item_type_id')
        //     ->leftJoin('merks', 'merks.id', '=', 'service_items.merk_id')
        //     ->leftJoin('users', 'users.id', '=', 'service_items.created_by_user_id')
        //     ->leftJoin('branch_offices', 'branch_offices.id', '=', 'users.branch_office_id');
        
        // $query = ServiceItem::query();

        $query = ServiceItem::with([
            'customer',
            'itemType',
            'merk',
            'creator.branchOffice',
            'serviceProcesses'
        ])->latest();

        return DataTables::of($query)
            ->addColumn('customer_name', function ($row) {
                return $row->customer ? $row->customer->name : '-';
            })
            ->addColumn('type', function ($row) {
                return $row->itemType ? $row->itemType->type_name : '-';
            })
            ->addColumn('merk', function ($row) {
                return $row->merk ? $row->merk->merk_name : '-';
            })
            ->addColumn('admin', function ($row) {
                if ($row->creator) {
                    $adminName = $row->creator->name;
                    $branchOffice = $row->creator->branchOffice ? $row->creator->branchOffice->name : '-';

                    return $adminName . '(' . $branchOffice . ')';
                }
                return '-';
            })
            ->addColumn('status', function ($row) {
                $latestProcess = $row->serviceProcesses->sortByDesc('created_at')->first();
                $status = $latestProcess ? $latestProcess->process_status : 'Pending';
                $statusSlug = Str::slug($status);

                return '<span class="status-badge status-' . $statusSlug . '">' . e($status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="actions">
                        <a href="' . route('activity.service_items.detail_activity_service_item', $row->id) . '" class="view-button">Lihat Detail</a>
                    </div>
                ';
            })
            // ->filterColumn('code', function($query, $keyword) {
            //     $query->where('service_items.code', 'like', "%{$keyword}%");
            // })
            // ->filterColumn('serial_number', function($query, $keyword) {
            //     $query->where('service_items.serial_number', 'like', "%{$keyword}%");
            // })
            // ->filterColumn('name', function($query, $keyword) {
            //     $query->where('service_items.name', 'like', "%{$keyword}%");
            // })

            // === FILTER KOLOM RELASI TANPA MENGHAPUS DATA NULL ===
            ->filterColumn('type', function($query, $keyword) {
                // $query->where('item_types.type_name', 'like', "%{$keyword}%");
                $query->whereHas('itemType', function($q) use ($keyword) {
                    $q->where('type_name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('merk', function($query, $keyword) {
                // $query->where('merks.merk_name', 'like', "%{$keyword}%");
                $query->whereHas('merk', function ($q) use ($keyword) {
                    $q->where('merk_name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('customer_name', function($query, $keyword) {
                // $query->where('customers.name', 'like', "%{$keyword}%");
                $query->whereHas('customer', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('admin', function($query, $keyword) {
                // $query->whereRaw("CONCAT(users.name, ' (', IFNULL(branch_offices.name, '(Tidak Ditemukan)'), ')') LIKE ?", ["%{$keyword}%"]);
                $query->whereHas('creator', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhereHas('branchOffice', function ($bq) use ($keyword) {
                            $bq->where('name', 'like', "%{$keyword}%");
                        });
                });
            })
            // === FILTER STATUS ===
            ->filter(function ($query) use ($request) {
                // if ($request->status_filter) {
                //     $query->whereHas('serviceProcesses', function($q) use ($request) {
                //         if ($request->status_filter === 'selesai') {
                //             $q->where('process_status', 'selesai');
                //         } elseif ($request->status_filter === 'tidak-bisa-diperbaiki') {
                //             $q->whereIn('process_status', ['Batal', 'Tidak bisa diperbaiki']);
                //         } elseif ($request->status_filter === 'proses-pengerjaan' ) {
                //             $q->whereNotIn('process_status', ['Selesai', 'Batal', 'Tidak bisa diperbaiki']);
                //         }
                //     });
                // }
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
            }, true)
            ->rawColumns(['action', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Get Data List Service Item Role Admin
     */
    public function getDataServiceItemAdmin(Request $request)
    {
        $loggedInUserId = Auth::id();
        $query = ServiceItem::with([
            'customer',
            'merk',
            'itemType',
            'creator',
        ])->where('created_by_user_id', $loggedInUserId)
        ->latest();

        return DataTables::of($query)
            ->addColumn('customer_name', function ($row) {
                return $row->customer ? $row->customer->name : '-';
            })
            ->addColumn('type', function ($row) {
                return $row->itemType ? $row->itemType->type_name : '-';
            })
            ->addColumn('merk', function ($row) {
                return $row->merk ? $row->merk->merk_name : '-';
            })
            ->addColumn('status', function ($row) {
                $latestProcess = $row->serviceProcesses->sortByDesc('created_at')->first();
                $status = $latestProcess ? $latestProcess->process_status : 'Pending';
                $statusSlug = Str::slug($status);

                return '<span class="status-badge status-' . $statusSlug . '">' . e($status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return '
                    <div class="actions">
                        <a href="'. route('service_items.show', $row->id)  . '" class="view-button">Lihat</a>
                        <a href="' . route('service_items.edit', $row->id) . '" class="edit-button">Edit</a>
                        <form action="' . route('service_items.destroy', $row->id) . '" method="POST" style="display:inline;">
                            ' . $csrf . '
                            ' . $method . '
                            <button type="submit" class="delete-button" onclick="return confirm(\'Anda yakin ingin menghapus barang servis ini?\')">Hapus</button>
                        </form>
                    </div>
                ';
            })
            ->filterColumn('customer_name', function ($query, $keyword) {
                $query->whereHas('customer', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('type', function ($query, $keyword) {
                $query->whereHas('itemType', function ($q) use ($keyword) {
                    $q->where('type_name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('merk', function ($query, $keyword) {
                $query->whereHas('merk', function ($q) use ($keyword) {
                    $q->where('merk_name', 'like', "%{$keyword}%");
                });
            })
            
            // === Filter Status ===
            ->filter(function ($query) use ($request) {
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
            }, true)
            ->rawColumns(['action', 'status'])
            ->addIndexColumn()
            ->make(true);
    }
}

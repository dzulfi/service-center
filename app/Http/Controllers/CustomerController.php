<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Role;
use App\Models\ServiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('customers.index');
    }

    /**
     * metode ini akan menampilkan daftar semua customer untuk Developer dan Superadmin
     */
    public function indexAll()
    {
        // $customers = Customer::all();
        return view('customers.index_all');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:customers',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string',
            'company' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Mitra Bisnis berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));

        // $user = Auth::user(); // dapatkan user yang sedang login

        // // $customer->load([
        // //     'serviceItems' => function ($query) use ($user) {
        // //         // jika user adalah admin, maka tampilkan service item yang dibuat oleh user tersebut 
        // //         if ($user->isAdmin()) {
        // //             $query->where('created_by_user_id', $user->id);
        // //         }
        // //         // eager load relasi-relasi yang dibutuhkan di blade untuk serviceItem
        // //         $query->with(['serviceProcesses', 'creator']);
        // //     }
        // // ]);

        // // Ambil serviceItems via query (bukan eiger load)
        // $serviceItemsQuery = $customer->serviceItems()->with(['serviceProcesses', 'creator']);
        
        // // Jika admin, filter berdasarkan user
        // if ($user->isAdmin()) {
        //     $serviceItemsQuery->where('created_by_user_id', $user->id);
        // }
        
        // $perPage = 10;
        // $serviceItems = $serviceItemsQuery->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
    }

    /**
     * detail aktivitas customer per id (rule: developer, superadmin)
     */
    public function showDetailAktivityCustomer(Customer $customer)
    {
        $serviceItems = $customer->serviceItems()->paginate(10);
        return view('customers.detail_activity_customer', compact('customer', 'serviceItems'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:10', Rule::unique('customers')->ignore($customer->id)],
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'company' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Data Mitra Bisnis berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Mitra Bisnis berhasil dihapus');
    }

    // Datatables Customer
    public function getDataCustomer(Request $request)
    {
        $query = Customer::query();

        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '
                    <div class="actions">
                    <a href="' . route('customers.show', $row->id) . '" class="view-button">Lihat</a>
                    <a href="' . route('customers.edit', $row->id) . '" class="edit-button">Edit</a>
                    <form action="' . route('customers.destroy', $row->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="delete-button" onclick="return confirm(\'Anda yakin ingin menghapus pelanggan ini?\')">Hapus</button>
                    </form></div>';
            })
            ->rawColumns(['action']) // agar kolom action tidak di-escape
            ->addIndexColumn()
            ->make(true);
    }

    // Datatables Customer Activity
    public function getDataCustomerActivity(Request $request)
    {
        $query = Customer::query();

        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '
                    <div class="actions">
                        <a href="' . route('activity.customers.detail_activity_customer', $row->id) . '" class="view-button">Lihat</a>
                    </div>
                ';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function getDataServiceItemCustomer(Customer $customer, Request $request)
    {
        // Cek user yang baru login
        $user = Auth::user();

        // Ambil serviceItems via query (bukan eiger load)
        $query = $customer->serviceItems()
            ->with([
                'serviceProcesses',
                'merk',
                'itemType',
            ])->where('created_by_user_id', $user->id);

        return DataTables::of($query)
            // ->addColumn('code', function ($row) {
            //     return $row->code ?? '-';
            // })
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

                return '<span class="status-badge status-' . $statusSlug . '">' .e($status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="actions">
                        <a href="' . route('service_items.show', $row->id) . '" class="view-button">Lihat</a>
                        <a href="' . route('service_items.edit', $row->id) . '" class="edit-button">Edit</a>
                    </div>
                ';
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

    public function getDataServiceItemActivityCustomerDetail(Customer $customer, Request $request)
    {
        $query = $customer->serviceItems()
            ->with([
                'serviceProcesses',
                'merk',
                'itemType',
                'creator.branchOffice',
                'rmaTechnicians',
            ]);

        return DataTables::of($query)
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

                    return $adminName .'(' . $branchOffice . ')';
                }
                return '-';
            })
            ->addColumn('technician', function ($row) {
                if ($row->rmaTechnicians->isNotEmpty()) {
                    return $row->rmaTechnicians->pluck('name')->join(', ');
                }

                return '
                    <div class="no-rma">
                        Belum ditangani
                    </div>
                ';
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
            ->filterColumn('technician', function ($query, $keyword) {
                $query->whereHas('rmaTechnicians', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('admin', function($query, $keyword) {
                $query->whereHas('creator', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhereHas('branchOffice', function ($bq) use ($keyword) {
                            $bq->where('name', 'like', "%{$keyword}%");
                        });
                });
            })
            
            // === FILTER STATUS ===
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
                    } elseif ($request->status_filter === 'proses-perbaikan') {
                        $query->where(function ($q) {
                            $q->whereDoesntHave('serviceProcesses') // belum ada proses sama seklai
                              ->orWhereHas('serviceProcesses', function ($sq) {
                                $sq->whereNotIn('process_status', ['Selesai', 'Batal', "Tidak bisa diperbaiki"]);
                              });
                        });
                    }
                }
            }, true)
            ->rawColumns(['status', 'action', 'technician'])
            ->addIndexColumn()
            ->make(true);
    }
}

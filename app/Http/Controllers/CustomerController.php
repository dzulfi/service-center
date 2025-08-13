<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Role;
use App\Models\ServiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user(); // dapatkan user yang sedang login

        // $customer->load([
        //     'serviceItems' => function ($query) use ($user) {
        //         // jika user adalah admin, maka tampilkan service item yang dibuat oleh user tersebut 
        //         if ($user->isAdmin()) {
        //             $query->where('created_by_user_id', $user->id);
        //         }
        //         // eager load relasi-relasi yang dibutuhkan di blade untuk serviceItem
        //         $query->with(['serviceProcesses', 'creator']);
        //     }
        // ]);

        // Ambil serviceItems via query (bukan eiger load)
        $serviceItemsQuery = $customer->serviceItems()->with(['serviceProcesses', 'creator']);

        // Jika admin, filter berdasarkan user
        if ($user->isAdmin()) {
            $serviceItemsQuery->where('created_by_user_id', $user->id);
        }

        $perPage = 10;
        $serviceItems = $serviceItemsQuery->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
        
        return view('customers.show', compact('customer', 'serviceItems'));
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
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Role;
use App\Models\ServiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::paginate(10);
        return view('customers.index', compact('customers'));
    }

    /**
     * metode ini akan menampilkan daftar semua customer untuk Developer dan Superadmin
     */
    public function indexAll()
    {
        $customers = Customer::paginate(10);
        return view('customers.index_all', compact('customers'));
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
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'kelurahan' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:255',
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
            'code' => ['required', 'string', 'max:10', Role::unique('customers')->ignore($customer->id)],
            'name' => 'required|string|max:255',
            'phone_number' => 'required|integer|max:15',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'kelurahan' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:255',
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
}

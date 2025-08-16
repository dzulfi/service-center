<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BranchOffice;
use App\Models\Role;
use App\Models\User;
use finfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd($users);
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $branchOffices = BranchOffice::all();
        return view('users.create', compact('roles', 'branchOffices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'branch_office_id' => 'required|exists:branch_offices,id',
            'phone_number' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'branch_office_id' => $request->branch_office_id,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['role', 'branchOffice']);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $branchOffices = BranchOffice::all();
        return view('users.edit', compact('user', 'roles', 'branchOffices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'branch_office_id' => 'nullable|exists:branch_offices,id',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'role_id' => $request->role_id,
            'branch_office_id' => $request->branch_office_id,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }

    public function getDataUser(Request $request)
    {
        $query = User::with(['role', 'branchOffice']);

        return DataTables::of($query)
            ->addColumn('branch_office', function ($row) {
                return $row->branchOffice ? $row->branchOffice->name : '-';
            })
            ->addColumn('role', function ($row) {
                return $row->role ? $row->role->name : '-';
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="actions">
                        <a href="' . route('users.show', $row->id) . '" class="view-button">Lihat</a>
                        <a href="' .route('users.edit', $row->id) . '" class="edit-button">Edit</a>
                        <form action="' . route('users.destroy', $row->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="delete-button" onclick="return confirm(\'Anda yakin ingin menghapus pengguna ini?\')">Hapus</button>
                        </form>
                    </div>
                ';
            })
            ->filterColumn('branch_office', function ($query, $keyword) {
                $query->whereHas('branchOffice', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('role', function ($query, $keyword) {
                $query->whereHas('role', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }
}

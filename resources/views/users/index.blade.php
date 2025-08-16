@extends('layouts.app') @section('title', 'Daftar Pengguna') @section('content')
    <div class="container">
        <h1>Daftar Pengguna</h1>

        @if (session('success'))
            <div class="message success-message">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('users.create') }}" class="add-button">Tambah Pengguna Baru</a>

        <table id="taleUsers">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Kantor Cabang</th>
                    <th>No. Telp</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection
@extends('layouts.app') @section('title', 'Daftar Mitra Bisnis') @section('content')
    <div class="container full-width">
        <h1>Daftar Customer</h1>

        @if (session('success'))
            <div class="message success-message">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('customers.create') }}" class="add-button">Tambah Mitra Bisnis Baru</a>

        <table id="customer-data-tables" class="display">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kode</th>
                    <th>No. Telepon</th>
                    <th>Perusahaan</th>
                    <th>Kota</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

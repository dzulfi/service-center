@extends('layouts.app') @section('title', 'Aktivitas: Semua Mitra Bisnis') @section('content')
    <div class="container full-width">
        <h1>Aktivitas: Daftar Mitra Bisnis</h1>

        @if (session('success'))
            <div class="message success-message">
                {{ session('success') }}
            </div>
        @endif

        <table id="customer-activity-data-tables" class="display">
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
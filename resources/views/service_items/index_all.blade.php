@extends('layouts.app') @section('title', 'Aktivitas: Semua Barang Servis') @section('content')
    <div class="container full-width">
        <h1>Aktivitas: Daftar Semua Barang Servis</h1>

        <div class="filter-menu">
            <button class="filter-btn active" data-filter="all">Semua</button>
            <button class="filter-btn" data-filter="selesai">Selesai</button>
            <button class="filter-btn" data-filter="tidak-bisa-diperbaiki">Tidak Bisa Diperbaiki</button>
            <button class="filter-btn" data-filter="proses-pengerjaan">Proses Pengerjaan</button>
        </div>
        
        <div class="table-responsive">
            <table id="serviceItemTableActivity">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Service</th>
                        <th>Serial Number</th>
                        <th>Nama Barang</th>
                        <th>Type</th>
                        <th>Merk</th>
                        <th>Mitra Bisnis</th>
                        <th>Admin</th>
                        <th>Status Pengerjaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
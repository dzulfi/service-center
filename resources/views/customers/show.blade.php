@extends('layouts.app') @section('title', 'Daftar Mitra Bisnis') @section('content')
    <div class="container full-width">
        <h1>Detail Customer</h1>

        <div class="detail-group">
            <strong>Kode</strong>
            <div class="space">:</div>
            <span>{{ $customer->code }}</span>
        </div>
        <div class="detail-group">
            <strong>Nama</strong>
            <div class="space">:</div>
            <span>{{ $customer->name }}</span>
        </div>
        <div class="detail-group">
            <strong>No. Telepon</strong> 
            <div class="space">:</div>
            <span>{{ $customer->phone_number ?? '-' }}</span>
        </div>
        <div class="detail-group">
            <strong>Perusahaan</strong> 
            <div class="space">:</div>
            <span>{{ $customer->company ?? '-' }}</span>
        </div>
        <div class="detail-group">
            <strong>Alamat</strong> 
            <div class="space">:</div>
            <span>
                {{ $customer->address ?? '-' }}, {{ $customer->kelurahan ?? '-' }}, {{ $customer->kecamatan ?? '-' }}, {{ $customer->kota ?? '-' }}
            </span>
        </div>

        <div class="actions">
            <a href="{{ route('customers.edit', $customer->id) }}" class="edit-button">Edit</a>
            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-button" onclick="return confirm('Anda yakin ingin menghapus mitra bisnis ini?')">Hapus</button>
            </form>
        </div>

        {{-- Informasi service milik customer --}}
        <h2 style="margin-top: 30px;">Barang Servis Milik Mitra Bisnis :</h2>

        {{-- Filter Menu --}}
        <div class="filter-menu">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="selesai">Selesai</button>
            <button class="filter-btn" data-filter="tidak-bisa-diperbaiki">Tidak Bisa Diperbaiki</button>
            <button class="filter-btn" data-filter="proses-pengerjaan">Proses Pengerjaan</button>
        </div>

        <div id="customer-table" data-id="{{ $customer->id ?? '' }}">
            <table id="serviceItemsTableCustomerShow">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Service</th>
                        <th>Serial Number</th>
                        <th>Nama Barang</th>
                        <th>Tipe</th>
                        <th>Merk</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection


@extends('layouts.app') @section('title', 'Daftar Mitra Bisnis') @section('content')
    <div class="container full-width">
        <h1>Detail Mitra Bisnis</h1>

        <div class="detail-group">
            <strong>Nama</strong> 
            <div class="space">:</div>
            <span>{{ $customer->name }}</span>
        </div>
        <div class="detail-group">
            <strong>Kode</strong>
            <div class="space">:</div>
            <span>{{ $customer->code }}</span>
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

        {{-- Informasi service milik customer --}}
        <h2 style="margin-top: 30px;">Barang Servis Milik Customer :</h2>
        {{-- Filter Menu --}}
        <div class="filter-menu">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="selesai">Selesai</button>
            <button class="filter-btn" data-filter="tidak-bisa-diperbaiki">Tidak Bisa Diperbaiki</button>
            <button class="filter-btn" data-filter="proses-perbaikan">Proses Perbaikan</button>
        </div>

        <div id="customer-table" data-id="{{ $customer->id ?? '' }}">
            <table id="serviceItemsTableActivityCustomerDetail">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Code Service</th>
                        <th>Serial Number</th>
                        <th>Nama Barang</th>
                        <th>Tipe</th>
                        <th>Merk</th>
                        <th>Kantor Cabang</th>
                        <th>Teknisi</th>
                        <th>Status Servis</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('.filter-btn').on('click', function() {
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');

            var filterValue = $(this).data('filter'); // Mendapatkan nilai data-filter

            $('#serviceItemsTable tbody tr').hide(); // Sembunyikan semua baris

            if (filterValue === 'all') {
                $('#serviceItemsTable tbody tr').show(); // Tampilkan semua jika filter 'all'
            } else {
                // Tampilkan baris yang memiliki data-filter-group sesuai dengan filterValue
                $('#serviceItemsTable tbody tr[data-filter-group="' + filterValue + '"]').show();
            }
        });
    });
</script> --}}
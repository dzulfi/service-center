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
        {{-- <label for="">Filter status service :</label> --}}
        <div class="filter-menu">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="selesai">Selesai</button>
            <button class="filter-btn" data-filter="tidak-bisa-diperbaiki">Tidak Bisa Diperbaiki</button>
            <button class="filter-btn" data-filter="proses-perbaikan">Proses Perbaikan</button>
        </div>

        @if ($customer->serviceItems->isEmpty())
            <p class="no-service-items">Belum ada barang servis untuk customer ini.</p>
        @else
            <table id="serviceItemsTable">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Code Service</th>
                        <th>Nama Barang</th>
                        <th>Tipe</th>
                        <th>Serial Number</th>
                        <th>Merk</th>
                        <th>Kantor Cabang</th>
                        <th>Ditangani Oleh</th>
                        <th>Status Servis</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($serviceItems as $item)
                        @php
                            $latestProcess = $item->serviceProcesses->sortByDesc('created_at')->first();
                            $status = $latestProcess ? $latestProcess->process_status : 'Pending';
                            $statusSlug = Str::slug($status); // Gunakan Str::slug untuk kelas CSS
                            $filterGroup = '';

                            if ($status === 'Selesai') {
                                $filterGroup = 'selesai';
                            } elseif ($status === 'Tidak bisa diperbaiki') { // Asumsi 'Batal' masuk ke 'Tidak Bisa Diperbaiki' atau bisa buat kategori sendiri
                                $filterGroup = 'tidak-bisa-diperbaiki';
                            } elseif ($status === 'Tidak Bisa Diperbaiki') { // Jika Anda memiliki status eksplisit ini
                                $filterGroup = 'tidak-bisa-diperbaiki';
                            } else { // Semua status selain Selesai dan Batal/Tidak Bisa Diperbaiki dianggap proses perbaikan
                                $filterGroup = 'proses-perbaikan';
                            }
                        @endphp
                        <tr data-filter-group="{{ $filterGroup }}">
                            <td>{{ ($serviceItems->currentPage() - 1) * $serviceItems->perPage() + $loop->iteration }}</td>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->itemType->type_name ?? '-' }}</td>
                            <td>{{ $item->serial_number ?? '-' }}</td>
                            <td>{{ $item->itemType->merk->merk_name ?? '-' }}</td>
                            <td>{{ $item->creator->branchOffice->name }}</td>
                            
                            @if ($item->serviceProcesses->isEmpty())
                                <td style="color: red; ">Belum ada</td>
                            @else
                                @foreach ($item->serviceProcesses as $process)
                                    <td>{{ $process->handler->name ?? '-' }}</td>
                                @endforeach
                            @endif
                            <td>
                                <span class="status-badge status-{{ $statusSlug }}">
                                    {{ $status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination-wrapper">
                {{ $serviceItems->links() }}
            </div>
        @endif
        {{-- <a href="{{ route('customers.index') }}" class="back-link">Kembali ke Daftar Pelanggan</a> --}}
    </div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
</script>
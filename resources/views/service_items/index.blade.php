@extends('layouts.app') @section('title', 'Daftar Mitra Bisnis') @section('content')
    <div class="container full-width">
        <h1>Daftar Barang Servis</h1>

        @if (session('success'))
            <div class="message success-message">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('service_items.create') }}" class="add-button">Tambah Barang Servis Baru</a>

        {{-- Filter Menu --}}
        <div class="filter-menu">
            <button class="filter-btn active" data-filter="all">Semua</button>
            <button class="filter-btn" data-filter="selesai">Selesai</button>
            <button class="filter-btn" data-filter="tidak-bisa-diperbaiki">Tidak Bisa Diperbaiki</button>
            <button class="filter-btn" data-filter="proses-pengerjaan">Proses Pengerjaan</button>
        </div>

        @if ($serviceItems->isEmpty())
            <p class="no-data">Belum ada barang servis yang terdaftar.</p>
        @else
            <div class="table-responsive">
                <table id="serviceItemsTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Customer</th>
                            <th>Nama Barang</th>
                            <th>Merk</th>
                            <th>Tipe Barang</th>
                            <th>Serial Number</th>
                            <th>Dibuat oleh</th>
                            <th>Status Pengerjaan</th> 
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($serviceItems as $item)
                            @php
                                $latestProcess = $item->serviceProcesses->sortByDesc('created_at')->first();
                                $status = $latestProcess ? $latestProcess->process_status : 'Pending';
                                $statusSlug = Str::slug($status);
                                $filterGroup = '';
    
                                if ($status === 'Selesai') {
                                    $filterGroup = 'selesai';
                                } elseif ($status === 'Batal' || $status === 'Tidak bisa diperbaiki') { // Pastikan 'Batal' masuk ke sini jika itu menandakan tidak bisa diperbaiki
                                    $filterGroup = 'tidak-bisa-diperbaiki';
                                } else { // Semua status selain Selesai, Batal, Tidak Bisa Diperbaiki dianggap proses pengerjaan
                                    $filterGroup = 'proses-pengerjaan';
                                }
                            @endphp
                            <tr data-filter-group="{{ $filterGroup }}">
                                <td>{{ ($serviceItems->currentPage() - 1) * $serviceItems->perPage() + $loop->iteration }}</td>
                                <td>{{ $item->code ?? '-' }}</td>
                                <td>
                                    @if ($item->customer)
                                        <a href="{{ route('customers.show', $item->customer->id) }}">{{ $item->customer->name }}</a>
                                    @else
                                        <span style="color: #999;">(Mitra Bisnis Tidak Ditemukan)</span>
                                    @endif
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->merk->merk_name ?? '-' }}</td>
                                <td>{{ $item->itemType->type_name ?? '-' }}</td>
                                <td>{{ $item->serial_number ?? '-' }}</td>
    
                                {{-- author yang membuat barang service oleh user siapa --}}
                                <td>{{ $item->creator->name ?? 'N/A' }}</td> 
                                
                                <td>
                                    {{-- LOGIKA STATUS PROSES TERAKHIR --}}
                                    @if ($latestProcess)
                                        <span class="status-badge status-{{ $statusSlug }}">
                                            {{ $status }}
                                        </span>
                                    @else
                                        <span class="status-badge status-pending">Pending</span>
                                    @endif
                                </td>
                                <td class="actions">
                                    <a href="{{ route('service_items.show', $item->id) }}" class="view-button">Lihat</a>
                                    <a href="{{ route('service_items.edit', $item->id) }}" class="edit-button">Edit</a>
                                    <form action="{{ route('service_items.destroy', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-button" onclick="return confirm('Anda yakin ingin menghapus barang servis ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrapper">
                {{ $serviceItems->links() }}
            </div>
        @endif
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
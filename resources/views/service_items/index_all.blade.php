<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang Servis</title>
    {{-- <style>
        /* Pastikan CSS ini ada di sini atau di file CSS eksternal Anda */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f7f6;
            color: #333;
        }
        .container {
            max-width: 100%;
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
        }
        .message {
            padding: 12px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .add-button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
        }
        .add-button:hover {
            background-color: #2980b9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #e9ecef;
            color: #555;
            font-weight: 600;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .actions {
            white-space: nowrap;
        }
        .actions a, .actions button {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9em;
            margin-right: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .actions a.view-button {
            background-color: #28a745;
            color: white;
        }
        .actions a.view-button:hover {
            background-color: #218838;
        }
        .actions a.edit-button {
            background-color: #ffc107;
            color: #333;
        }
        .actions a.edit-button:hover {
            background-color: #e0a800;
        }
        .actions button.delete-button {
            background-color: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
        }
        .actions button.delete-button:hover {
            background-color: #c82333;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #777;
            font-style: italic;
        }
        .nav-links {
            margin-top: 30px;
            text-align: center;
        }
        .nav-links a {
            color: #3498db;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }
        .nav-links a:hover {
            text-decoration: underline;
        }
        /* Status colors - pastikan ini konsisten dengan CSS Anda */
        .status-badge {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 0.8em;
            font-weight: bold;
            text-transform: uppercase;
            color: #fff; /* Default text color for badges */
        }
        .status-pending { background-color: #ffeb3b; color: #333; } /* Amber */
        .status-diagnosa { background-color: #673ab7; } /* Deep Purple */
        .status-proses-pengerjaan { background-color: #2196f3; } /* Blue */
        .status-menunggu-sparepart { background-color: #ff9800; } /* Orange */
        .status-selesai { background-color: #4caf50; } /* Green */
        .status-tidak-bisa-diperbaiki { background-color: #f44336; } /* Red */

        /* Filter menu styles */
        .filter-menu {
            margin-bottom: 20px;
            text-align: center;
        }
        .filter-menu button {
            background-color: #5bc0de; /* Info blue */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            margin: 0 5px;
            transition: background-color 0.3s ease;
        }
        .filter-menu button:hover, .filter-menu button.active {
            background-color: #31b0d5;
        }
    </style> --}}
</head>
<body>
    @extends('layouts.app') @section('title', 'Aktivitas: Semua Barang Servis') @section('content')
        <div class="container">
            <h1>Aktivitas: Daftar Semua Barang Servis</h1>

            <div class="filter-menu"> {{-- Sertakan juga filter untuk memudahkan --}}
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
                                <th>Kode Service</th>
                                <th>Customer</th>
                                <th>Nama Barang</th>
                                <th>Dibuat Oleh</th>
                                <th>Serial Number</th>
                                <th>Merk</th>
                                <th>Tipe Barang</th>
                                <th>Ditangani</th>
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
                                    } elseif ($status === 'Batal' || $status === 'Tidak bisa diperbaiki') {
                                        $filterGroup = 'tidak-bisa-diperbaiki';
                                    } else {
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
                                    <td>{{ $item->creator->name ?? 'N/A' }} ({{ $item->creator->branchOffice->name }})</td>
                                    <td>{{ $item->serial_number ?? '-' }}</td>
                                    <td>{{ $item->merk->merk_name }}</td>
                                    <td>{{ $item->itemType->type_name ?? '-' }}</td>
                                    @if ($item->serviceProcesses->isEmpty())
                                        <td style="color: red;">Belum ada</td>
                                    @else
                                        @foreach ($item->serviceProcesses as $process)
                                            <td>{{ $process->handler->name }}</td>
                                        @endforeach
                                    @endif

                                    <td>
                                        @if ($latestProcess)
                                            <span class="status-badge status-{{ $statusSlug }}">
                                                {{ $status }}
                                            </span>
                                        @else
                                            <span class="status-badge status-pending">Pending</span>
                                        @endif
                                    </td>
                                    <td class="actions">
                                        <a href="{{ route('activity.service_items.detail_activity_service_item', $item->id) }}" class="view-button">Lihat Detail</a>
                                        {{-- Tidak ada tombol Edit/Hapus di sini --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination-wrapper">
                        {{ $serviceItems->links() }}
                    </div>
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
</body>
</html>
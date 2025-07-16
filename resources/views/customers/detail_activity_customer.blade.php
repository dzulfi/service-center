<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Customer: {{ $customer->name }}</title>
    {{-- <style>
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
        h2 {
            color: #2c3e50;
            margin-top: 40px;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .detail-group {
            display: flex;
            margin-bottom: 15px;
            align-items: flex-start;
        }
        .detail-group strong {
            flex: 0 0 150px; /* Fixed width for labels */
            color: #555;
            font-weight: 600;
        }
        .detail-group span {
            flex: 1;
            color: #333;
        }
        .actions {
            margin-top: 30px;
            text-align: center;
        }
        .actions a, .actions button {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin: 0 10px;
            transition: background-color 0.3s ease, color 0.3s ease;
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
        .back-link {
            display: inline-block;
            margin-top: 30px;
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        .back-link:hover {
            color: #2980b9;
            text-decoration: underline;
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
        .service-item-actions a {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9em;
            margin-right: 5px;
            background-color: #28a745;
            color: white;
            transition: background-color 0.3s ease;
        }
        .service-item-actions a:hover {
            background-color: #218838;
        }
        .no-service-items {
            text-align: center;
            padding: 20px;
            color: #777;
            font-style: italic;
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
            color: rgb(0, 0, 0);
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
    @extends('layouts.app') @section('title', 'Daftar Pelanggan') @section('content')
        <div class="container">
            <h1>Detail Customer</h1>

            <div class="detail-group">
                <strong>Nama Pelanggan:</strong> <span>{{ $customer->name }}</span>
            </div>
            <div class="detail-group">
                <strong>No. Telepon:</strong> <span>{{ $customer->phone_number ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Perusahaan:</strong> <span>{{ $customer->company ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Alamat:</strong> <span>{{ $customer->address ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Kelurahan:</strong> <span>{{ $customer->kelurahan ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Kecamatan:</strong> <span>{{ $customer->kecamatan ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Kota:</strong> <span>{{ $customer->kota ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Dibuat Pada:</strong> <span>{{ $customer->created_at->format('d M Y H:i') }}</span>
            </div>
            <div class="detail-group">
                <strong>Diperbarui Pada:</strong> <span>{{ $customer->updated_at->format('d M Y H:i') }}</span>
            </div>

            {{-- 
                Informasi service milik customer
            --}}
            <h2>Barang Servis Milik Customer :</h2>
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
                            <th>ID</th>
                            <th>Nama Barang</th>
                            <th>Tipe</th>
                            <th>Serial Number</th>
                            <th>Merk</th>
                            <th>Kantor Cabang Pembuat</th>
                            <th>Ditangani Oleh</th>
                            <th>Status Servis</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customer->serviceItems as $item)
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
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->type ?? '-' }}</td>
                                <td>{{ $item->serial_number ?? '-' }}</td>
                                <td>{{ $item->merk ?? '-' }}</td>
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
            @endif
            <a href="{{ route('customers.index') }}" class="back-link">Kembali ke Daftar Pelanggan</a>
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
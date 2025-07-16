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
    @extends('layouts.app') @section('title', 'RMA: Barang Siap Kirim Kembali ke Cabang') @section('content')
        <div class="container">
            <h1>RMA: Barang Siap Kirim Kembali ke Cabang</h1>

            @if (session('success'))
                <div class="message success-message">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="message error-message">
                    {{ session('error') }}
                </div>
            @endif

            @if ($serviceItems->isEmpty())
                <p class="no-data">Tidak ada barang yang siap dikirim kembali ke cabang.</p>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Serial Number</th>
                                <th>Pelanggan</th>
                                <th>Status Proses</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($serviceItems as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->serial_number ?? '-' }}</td>
                                    <td>{{ $item->customer->name ?? '-' }}</td>
                                    <td>
                                        @if ($item->latestServiceProcess)
                                            <span class="status-badge status-{{ Str::slug($item->latestServiceProcess->process_status) }}">
                                                {{ $item->latestServiceProcess->process_status }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="actions">
                                        <a href="{{ route('shipments.rma.outbound_from_rma.create', $item->id) }}" class="add-button" style="background-color: #007bff;">Kirim Balik Sekarang</a>
                                        <a href="{{ route('service_items.show', $item->id) }}" class="view-button">Lihat Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endsection
</body>
</html>
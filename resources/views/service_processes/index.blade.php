<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang Servis (Siap Dikerjakan)</title>
    <style>
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
        .actions a {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9em;
            margin-right: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .actions a.work-button {
            background-color: #28a745; /* Hijau untuk Kerjakan */
            color: white;
        }
        .actions a.work-button:hover {
            background-color: #218838;
        }
        .no-data {
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
    </style>
</head>
<body>
    @extends('layouts.app') @section('title', 'Daftar Pelanggan') @section('content')
        <div class="container">
            <h1>Antrian Barang Service</h1>

            @if (session('success'))
                <div class="message success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if ($serviceItems->isEmpty())
                <p class="no-data">Tidak ada barang servis yang perlu dikerjakan atau sedang dalam proses.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>ID Barang</th>
                            <th>Nama Barang</th>
                            <th>Serial Number</th>
                            <th>Pelanggan</th>
                            <th>Analisa Kerusakan Awal</th>
                            <th>Kerusakan</th>
                            <th>Solusi</th>
                            <th>Keterangan</th>
                            <th>Status Terakhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach ($serviceItems as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->serial_number ?? '-' }}</td>
                                <td>
                                    @if ($item->customer)
                                        <a href="{{ route('customers.show', $item->customer->id) }}">{{ $item->customer->name }}</a>
                                    @else
                                        <span style="color: #999;">(Tidak Ditemukan)</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($item->analisa_kerusakan ?? '-', 50) }}</td>
                                
                                {{-- @foreach ($item->serviceProcesses as $service) --}}
                                    @php
                                        $latestProcess = $item->serviceProcesses->sortByDesc('created_at')->first();
                                    @endphp
                                    @if ($latestProcess)
                                        <td>{{ $latestProcess->damage_analysis_detail ?? '-' }}</td>
                                        <td>{{ $latestProcess->solution ?? '-' }}</td>
                                        <td>{{ $latestProcess->keterangan ?? '-' }}</td>
                                    @else
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    @endif
                                {{-- @endforeach --}}

                                <td>
                                    @php
                                        $latestProcess = $item->serviceProcesses->sortByDesc('created_at')->first();
                                    @endphp
                                    @if ($latestProcess)
                                        <span class="status-badge status-{{ Str::slug($latestProcess->process_status) }}">
                                            {{ $latestProcess->process_status }}
                                        </span>
                                    @else
                                        <span class="status-badge status-pending">Pending</span>
                                    @endif
                                </td>
                                <td class="actions">
                                    <a href="{{ route('service_processes.work_on', $item->id) }}" class="work-button">Kerjakan</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endsection
</body>
</html>
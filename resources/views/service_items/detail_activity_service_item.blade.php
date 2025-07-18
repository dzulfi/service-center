<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Barang Servis: {{ $serviceItem->item_name }}</title>
    {{-- <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f7f6;
            color: #333;
        }
        .container {
            max-width: 800px;
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
            text-align: center;
        }
        .detail-group {
            display: flex;
            margin-bottom: 15px;
            align-items: flex-start;
        }
        .detail-group strong {
            flex: 0 0 180px; /* Lebar tetap untuk label */
            color: #555;
            font-weight: 600;
        }
        .detail-group span, .detail-group a {
            flex: 1;
            color: #333;
        }
        .detail-group a {
            color: #3498db;
            text-decoration: none;
        }
        .detail-group a:hover {
            text-decoration: underline;
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

    </style> --}}
</head>
<body>
    @extends('layouts.app') @section('title', 'Daftar Pelanggan') @section('content')
        <div class="container">
            <h1>Detail Barang Servis</h1>

            <div class="detail-group">
                <strong>Pelanggan:</strong>
                <span>
                    @if ($serviceItem->customer)
                        <a href="{{ route('activity.customers.detail_activity_customer', $serviceItem->customer->id) }}">{{ $serviceItem->customer->name }}</a>
                    @else
                        <span style="color: #999;">(Tidak Ditemukan)</span>
                    @endif
                </span>
            </div>
            <div class="detail-group">
                <strong>Nama Barang:</strong> 
                <span>{{ $serviceItem->name }}</span>
            </div>
            <div class="detail-group">
                <strong>Tipe Barang:</strong> 
                <span>{{ $serviceItem->type ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Serial Number:</strong> 
                <span>{{ $serviceItem->serial_number ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Kode Service</strong>
                <span>{{ $serviceItem->code }}</span>
            </div>
            <div class="detail-group">
                <strong>Analisa Kerusakan:</strong> 
                <span>{{ $serviceItem->analisis_kerusakan ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Merk:</strong> 
                {{-- <span>{{ $serviceItem->merk ?? '-' }}</span> --}}
                <span>{{ $serviceItem->itemType->merk->merk_name }}</span>
            </div>
            <div class="detail-group">
                <strong>Service Masuk:</strong> 
                <span>{{ $serviceItem->created_at->format('d M Y H:i') }}</span>
            </div>

            <div class="detail-group">
                <strong>Kirim ke RMA:</strong>
                {{-- <span>{{ $kirimKeRma ? $kirimKeRma->format('d M Y H:i') : '-' }}</span> --}}
                <span>{{ $serviceItem->kirim_ke_rma?->format('d M Y H:i') ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Diterima RMA</strong>
                {{-- <span>{{ $diterimaRma ? $diterimaRma->format('d M Y H:i') : '-' }}</span> --}}
                <span>{{ $serviceItem->diterima_rma?->format('d M Y H:i') ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <Strong>Mulai Dikerjakan:</Strong>
                {{-- <span>{{ $mulaiDikerjakan ? $mulaiDikerjakan->format('d M Y H:i') : '-' }}</span> --}}
                <span>{{ $serviceItem->mulai_dikerjakan?->format('d M Y H:i') ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Selesai Dikerjakan:</strong>
                {{-- <span>{{ $selesaiDikerjakan ? $selesaiDikerjakan->format('d M Y H:i') : '-' }}</span> --}}
                <span>{{ $serviceItem->selesai_dikerjakan?->format('d M Y H:i') ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Kirim Ke Admin:</strong>
                {{-- <span>{{ $dikirimKembali ? $dikirimKembali->format('d M Y H:i') : '-' }}</span> --}}
                <span>{{ $serviceItem->dikirim_kembali?->format('d M Y H:i') ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Diterima Admin:</strong>
                {{-- <span>{{ $diterimaCabang ? $diterimaCabang->format('d M Y H:i') : '-' }}</span> --}}
                <span>{{ $serviceItem->diterima_cabang?->format('d M Y H:i') ?? '-' }}</span>
            </div>

            <h2>Tahap Proses Pengerjaan Service Oleh Tim RMA</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Ditangani Oleh</th>
                        <th>Kerusakan</th>
                        <th>Solusi</th>
                        <th>Keterangan</th>
                        <th>Status Pengerjaan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($serviceItem->serviceProcesses->sortBy('created_at') as $process)
                        <td>{{ $no++ }}</td>
                        <td>{{ $process->handler->name }}</td>
                        <td>{{ Str::limit($process->damage_analysis_detail ?? '-', 50) }}</td>
                        <td>{{ Str::limit($process->solution ?? '-', 50) }}</td>
                        <td>{{ Str::limit($process->keterangan ?? '-', 50) }}</td>
                        <td>
                            <span class="status-badge status-{{ Str::slug($process->process_status) }}">
                                {{ $process->process_status }}
                            </span>
                        </td>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endsection
</body>
</html>
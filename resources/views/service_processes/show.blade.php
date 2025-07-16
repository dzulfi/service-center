<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Proses Servis: {{ $serviceProcess->serviceItem->item_name ?? 'N/A' }}</title>
    {{-- <style>
        /* CSS yang sama dengan show pelanggan/barang servis sebelumnya */
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
        .detail-group {
            display: flex;
            margin-bottom: 15px;
            align-items: flex-start;
        }
        .detail-group strong {
            flex: 0 0 200px; /* Lebar tetap untuk label */
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

        /* Status colors */
        .status-pending { background-color: #ffeb3b; color: #333; } /* Amber */
        .status-diagnosa { background-color: #673ab7; color: #fff; } /* Deep Purple */
        .status-proses-pengerjaan { background-color: #2196f3; color: #fff; } /* Blue */
        .status-menunggu-sparepart { background-color: #ff9800; color: #fff; } /* Orange */
        .status-selesai { background-color: #4caf50; color: #fff; } /* Green */
        .status-batal { background-color: #f44336; color: #fff; } /* Red */
        .status-badge {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 0.9em;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style> --}}
</head>
<body>
    @extends('layouts.app') @section('title', 'Daftar Pelanggan') @section('content')
        <div class="container">
            <h1>Detail Proses Servis</h1>

            <div class="detail-group">
                <strong>Barang Servis:</strong>
                <span>
                    @if ($serviceProcess->serviceItem)
                        <a href="{{ route('service_items.show', $serviceProcess->serviceItem->id) }}">
                            {{ $serviceProcess->serviceItem->name }} (SN: {{ $serviceProcess->serviceItem->serial_number ?? '-' }})
                        </a>
                    @else
                        <span style="color: #999;">(Barang Servis Tidak Ditemukan)</span>
                    @endif
                </span>
            </div>
            <div class="detail-group">
                <strong>Pelanggan:</strong>
                <span>
                    @if ($serviceProcess->serviceItem && $serviceProcess->serviceItem->customer)
                        <a href="{{ route('customers.show', $serviceProcess->serviceItem->customer->id) }}">
                            {{ $serviceProcess->serviceItem->customer->name }}
                        </a>
                    @else
                        <span style="color: #999;">(Pelanggan Tidak Ditemukan)</span>
                    @endif
                </span>
            </div>
            <div class="detail-group">
                <strong>Analisa Kerusakan Detail:</strong> <span>{{ $serviceProcess->damage_analysis_detail ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Solusi / Tindakan:</strong> <span>{{ $serviceProcess->solution ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Status Proses Pengerjaan:</strong>
                <span>
                    <span class="status-badge status-{{ Str::slug($serviceProcess->process_status) }}">
                        {{ $serviceProcess->process_status }}
                    </span>
                </span>
            </div>
            <div class="detail-group">
                <strong>Keterangan Tambahan:</strong> <span>{{ $serviceProcess->keterangan ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Ditangani oleh:</strong>
                <span>{{ $serviceProcess->handler->name ?? 'Belum ada' }}</span>
            </div>
            <div class="detail-group">
                <strong>Dibuat Pada:</strong> <span>{{ $serviceProcess->created_at->format('d M Y H:i') }}</span>
            </div>
            <div class="detail-group">
                <strong>Diperbarui Pada:</strong> <span>{{ $serviceProcess->updated_at->format('d M Y H:i') }}</span>
            </div>

            <div class="actions">
                <a href="{{ route('service_processes.edit', $serviceProcess->id) }}" class="edit-button">Edit Proses Servis</a>
                <form action="{{ route('service_processes.destroy', $serviceProcess->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-button" onclick="return confirm('Anda yakin ingin menghapus proses servis ini?')">Hapus Proses Servis</button>
                </form>
            </div>

            <a href="{{ route('service_processes.index') }}" class="back-link">Kembali ke Daftar Proses Servis</a>
        </div>
    @endsection
</body>
</html>
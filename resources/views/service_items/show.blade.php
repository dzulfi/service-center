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
                        <a href="{{ route('customers.show', $serviceItem->customer->id) }}">{{ $serviceItem->customer->name }}</a>
                    @else
                        <span style="color: #999;">(Tidak Ditemukan)</span>
                    @endif
                </span>
            </div>
            <div class="detail-group">
                <strong>Nama Barang:</strong> <span>{{ $serviceItem->name }}</span>
            </div>
            <div class="detail-group">
                <strong>Tipe Barang:</strong> <span>{{ $serviceItem->type ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Serial Number:</strong> <span>{{ $serviceItem->serial_number ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Kode Service</strong> <span>{{ $serviceItem->code ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Analisa Kerusakan:</strong> <span>{{ $serviceItem->analisis_kerusakan ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Merk:</strong> <span>{{ $serviceItem->merk ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Dibuat Pada:</strong> <span>{{ $serviceItem->created_at->format('d M Y H:i') }}</span>
            </div>
            <div class="detail-group">
                <strong>Diperbarui Pada:</strong> <span>{{ $serviceItem->updated_at->format('d M Y H:i') }}</span>
            </div>

            <div class="actions">
                <a href="{{ route('service_items.edit', $serviceItem->id) }}" class="edit-button">Edit Barang Servis</a>
                <form action="{{ route('service_items.destroy', $serviceItem->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-button" onclick="return confirm('Anda yakin ingin menghapus barang servis ini?')">Hapus Barang Servis</button>
                </form>
            </div>

            <a href="{{ route('service_items.index') }}" class="back-link">Kembali ke Daftar Barang Servis</a>
        </div>
    @endsection
</body>
</html>
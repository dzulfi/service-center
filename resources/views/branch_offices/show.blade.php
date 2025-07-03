<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kantor Cabang: {{ $branchOffice->name }}</title>
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
    </style>
</head>
<body>
    @extends('layouts.app') @section('title', 'Detail Kantor Cabang: ' . $branchOffice->name) @section('content')
        <div class="container">
            <h1>Detail Kantor Cabang</h1>

            <div class="detail-group">
                <strong>Nama Cabang:</strong> <span>{{ $branchOffice->name }}</span>
            </div>
            <div class="detail-group">
                <strong>Alamat:</strong> <span>{{ $branchOffice->address }}</span>
            </div>
            <div class="detail-group">
                <strong>Kelurahan:</strong> <span>{{ $branchOffice->sub_district ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Kecamatan:</strong> <span>{{ $branchOffice->district ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Kota:</strong> <span>{{ $branchOffice->city }}</span>
            </div>
            <div class="detail-group">
                <strong>Dibuat Pada:</strong> <span>{{ $branchOffice->created_at->format('d M Y H:i') }}</span>
            </div>
            <div class="detail-group">
                <strong>Diperbarui Pada:</strong> <span>{{ $branchOffice->updated_at->format('d M Y H:i') }}</span>
            </div>

            <div class="action-links">
                <a href="{{ route('branch_offices.edit', $branchOffice->id) }}" class="edit-button">Edit Cabang</a>
                <form action="{{ route('branch_offices.destroy', $branchOffice->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-button" onclick="return confirm('Anda yakin ingin menghapus cabang ini?')">Hapus Cabang</button>
                </form>
            </div>

            <a href="{{ route('branch_offices.index') }}" class="back-link">Kembali ke Daftar Cabang</a>
        </div>
    @endsection
</body>
</html>
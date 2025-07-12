<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Barang Servis: {{ $sparepart->name }}</title>
    <style>
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
    </style>
</head>
<body>
    @extends('layouts.app') @section('title', 'Detail Sparepart: ' . $sparepart->name) @section('content')
        <div class="container">
            <h1>Detail Sparepart</h1>

            @if (session('success'))
                <div class="message success-maeesage">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="message error-message">
                    {{ session('error') }}
                </div>
            @endif

            <div class="detail-group">
                <strong>Code Sparepart:</strong>
                <span>{{ $sparepart->code }}</span>
            </div>
            <div class="detail-group">
                <strong>Name Sparepart:</strong>
                <span>{{ $sparepart->name }}</span>
            </div>
            <div class="detail-group">
                <strong>Gambar:</strong>
                @if ($sparepart->image_path)
                    <img src="{{ Storage::url($sparepart->image_path) }}" alt="{{ $sparepart->name }}" style="max-width: 200px; height: auto; display: block; margin-top: 10px;">
                @else
                    <span>Tidak ada gambar</span>
                @endif
            </div>
            <div class="detail-group">
                <strong>Keterangan:</strong>
                <span>{{ $sparepart->description }}</span>
            </div>
        </div>
    @endsection
</body>
</html>
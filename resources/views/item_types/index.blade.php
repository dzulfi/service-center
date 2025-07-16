<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Merk</title>
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
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
            overflow: hidden; /* For rounded corners */
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
            white-space: nowrap; /* Keep buttons on one line */
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
    </style> --}}
</head>
<body>
    @extends('layouts.app') @section('title', 'Daftar Tipe Barang') @section('content')
        <div class="container">
            <h1>Daftar Tipe Barang</h1>

            @if (session('success'))
                <div class="message success-message">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('item_types.create') }}" class="add-button">Tambah Tipe Barang</a>

            @if ($itemTypes->isEmpty())
                <p class="no-data">Belum ada Tipe barang yang terdaftar</p>
            @else
                <table class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Merk</th>
                                <th>Tipe Barang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($itemTypes as $itemType)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $itemType->merk->merk_name }}</td>
                                    <td>{{ $itemType->type_name }}</td>
                                    <td class="actions">
                                        <a href="#" class="view-button">Lihat</a>
                                        <a href="{{ route('item_types.edit', $itemType->id) }}" class="edit-button">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </table>
            @endif
        </div>
    @endsection
</body>
</html>
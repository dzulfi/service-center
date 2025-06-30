<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pelanggan: {{ $customer->customer_name }}</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Detail Pelanggan</h1>

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

        <div class="actions">
            <a href="{{ route('customers.edit', $customer->id) }}" class="edit-button">Edit Pelanggan</a>
            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-button" onclick="return confirm('Anda yakin ingin menghapus pelanggan ini?')">Hapus Pelanggan</button>
            </form>
        </div>

        <h2>Barang Servis Milik Pelanggan Ini</h2>
        @if ($customer->serviceItems->isEmpty())
            <p class="no-service-items">Belum ada barang servis untuk pelanggan ini.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Tipe</th>
                        <th>Serial Number</th>
                        <th>Merk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customer->serviceItems as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->type ?? '-' }}</td>
                            <td>{{ $item->serial_number ?? '-' }}</td>
                            <td>{{ $item->merk ?? '-' }}</td>
                            <td class="service-item-actions">
                                <a href="{{ route('service_items.show', $item->id) }}">Lihat</a>
                                <a href="{{ route('service_items.edit', $item->id) }}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <a href="{{ route('customers.index') }}" class="back-link">Kembali ke Daftar Pelanggan</a>
    </div>
</body>
</html>
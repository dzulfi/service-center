<!DOCTYPE html>
<html>
<head>
    <title>Daftar Barang Servis</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .success-message { color: green; margin-bottom: 15px; }
        .action-links a, .action-links form { margin-right: 10px; display: inline-block; }
        button { padding: 5px 10px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #c82333; }
        .add-button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; margin-bottom: 15px; }
        .add-button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h1>Daftar Barang Servis</h1>

    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('service_items.create') }}" class="add-button">Tambah Barang Servis Baru</a>

    @if ($serviceItems->isEmpty())
        <p>Belum ada barang servis yang terdaftar.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Pelanggan</th>
                    <th>Tipe Barang</th>
                    <th>Serial Number</th>
                    <th>Merk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($serviceItems as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>
                            @if ($item->customer)
                                <a href="{{ route('customers.show', $item->customer->id) }}">{{ $item->customer->name }}</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $item->type ?? '-' }}</td>
                        <td>{{ $item->serial_number ?? '-' }}</td>
                        <td>{{ $item->merk ?? '-' }}</td>
                        <td class="action-links">
                            <a href="{{ route('service_items.show', $item->id) }}">Lihat</a> |
                            <a href="{{ route('service_items.edit', $item->id) }}">Edit</a> |
                            <form action="{{ route('service_items.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Anda yakin ingin menghapus barang servis ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <br>
    <a href="{{ route('customers.index') }}">Kembali ke Daftar Pelanggan</a>
</body>
</html>
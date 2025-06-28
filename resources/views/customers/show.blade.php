<!DOCTYPE html>
<html>
<head>
    <title>Detail Pelanggan: {{ $customer->customer_name }}</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .detail-item { margin-bottom: 10px; }
        .detail-item strong { display: inline-block; width: 120px; }
        .action-links a, .action-links form { margin-right: 10px; display: inline-block; }
        button { padding: 8px 12px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #c82333; }
        .back-link { margin-top: 20px; display: block; }
    </style>
</head>
<body>
    <h1>Detail Pelanggan</h1>

    <div class="detail-item">
        <strong>Nama Pelanggan:</strong> {{ $customer->name }}
    </div>
    <div class="detail-item">
        <strong>No. Telepon:</strong> {{ $customer->phone_number ?? '-' }}
    </div>
    <div class="detail-item">
        <strong>Perusahaan:</strong> {{ $customer->company ?? '-' }}
    </div>
    <div class="detail-item">
        <strong>Alamat:</strong> {{ $customer->address ?? '-' }}
    </div>
    <div class="detail-item">
        <strong>Kelurahan:</strong> {{ $customer->kelurahan ?? '-' }}
    </div>
    <div class="detail-item">
        <strong>Kecamatan:</strong> {{ $customer->kecamatan ?? '-' }}
    </div>
    <div class="detail-item">
        <strong>Kota:</strong> {{ $customer->kota ?? '-' }}
    </div>
    <div class="detail-item">
        <strong>Dibuat Pada:</strong> {{ $customer->created_at->format('d M Y H:i') }}
    </div>
    <div class="detail-item">
        <strong>Diperbarui Pada:</strong> {{ $customer->updated_at->format('d M Y H:i') }}
    </div>

    <div class="action-links">
        <a href="{{ route('customers.edit', $customer->id) }}">Edit Pelanggan</a>
        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Anda yakin ingin menghapus pelanggan ini?')">Hapus Pelanggan</button>
        </form>
    </div>

    <h2 style="margin-top: 30px;">Barang Servis Milik Pelanggan Ini:</h2>
    @if ($customer->serviceItems->isEmpty())
        <p>Belum ada barang servis untuk pelanggan ini.</p>
    @else
        <table border="1" style="width: 100%; border-collapse: collapse;">
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
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->item_type ?? '-' }}</td>
                        <td>{{ $item->serial_number ?? '-' }}</td>
                        <td>{{ $item->brand ?? '-' }}</td>
                        <td>
                            <a href="{{ route('service_items.show', $item->id) }}">Lihat</a> |
                            <a href="{{ route('service_items.edit', $item->id) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <a href="{{ route('customers.index') }}" class="back-link">Kembali ke Daftar Pelanggan</a>
</body>
</html>
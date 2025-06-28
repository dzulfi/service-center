<!DOCTYPE html>
<html>
<head>
    <title>Detail Barang Servis: {{ $serviceItem->name }}</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .detail-item { margin-bottom: 10px; }
        .detail-item strong { display: inline-block; width: 150px; }
        .action-links a, .action-links form { margin-right: 10px; display: inline-block; }
        button { padding: 8px 12px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #c82333; }
        .back-link { margin-top: 20px; display: block; }
    </style>
</head>
<body>
    <h1>Detail Barang Servis</h1>

    <div class="detail-item">
        <strong>Pelanggan:</strong> <a href="{{ route('customers.show', $serviceItem->customer->id) }}">{{ $serviceItem->customer->name }}</a>
    </div>
    <div class="detail-item">
        <strong>Nama Barang:</strong> {{ $serviceItem->name }}
    </div>
    <div class="detail-item">
        <strong>Tipe Barang:</strong> {{ $serviceItem->type ?? '-' }}
    </div>
    <div class="detail-item">
        <strong>Serial Number:</strong> {{ $serviceItem->serial_number ?? '-' }}
    </div>
    <div class="detail-item">
        <strong>Analisa Kerusakan:</strong> {{ $serviceItem->analisa_kerusakan ?? '-' }}
    </div>
    <div class="detail-item">
        <strong>Merk:</strong> {{ $serviceItem->merk ?? '-' }}
    </div>
    <div class="detail-item">
        <strong>Jumlah Item:</strong> {{ $serviceItem->jumlah_item ?? '-' }}
    </div>
    <div class="detail-item">
        <strong>Dibuat Pada:</strong> {{ $serviceItem->created_at->format('d M Y H:i') }}
    </div>
    <div class="detail-item">
        <strong>Diperbarui Pada:</strong> {{ $serviceItem->updated_at->format('d M Y H:i') }}
    </div>

    <div class="action-links">
        <a href="{{ route('service_items.edit', $serviceItem->id) }}">Edit Barang Servis</a>
        <form action="{{ route('service_items.destroy', $serviceItem->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Anda yakin ingin menghapus barang servis ini?')">Hapus Barang Servis</button>
        </form>
    </div>

    <a href="{{ route('service_items.index') }}" class="back-link">Kembali ke Daftar Barang Servis</a>
</body>
</html>
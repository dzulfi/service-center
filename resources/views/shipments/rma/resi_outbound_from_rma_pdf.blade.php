<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resi Pengiriman Kembali ke Admin Cabang</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        h2 { margin-bottom: 0; }
    </style>
</head>
<body>
    <h2>Resi Pengiriman ke Admin Cabang</h2>

    <p><strong>No Resi:</strong> {{ $shipment->resi_number }}</p>
    <p><strong>Tanggal Pembuatan:</strong> {{ $shipment->created_at->format('d-m-Y') }}</p>
    <p><strong>Pengiriman:</strong> {{ $shipment->responsibleUser->name }}</p>
    <p><strong>Gambar</strong></p>
    <p><strong>Notes:</strong> {{ $shipment->notes ?? '-' }}</p>

    <h3>Daftar Barang:</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Code</th>
                <th>Serial Number</th>
                <th>Nama</th>
                <th>Tipe Barang</th>
                <th>Merk</th>
                <th>Nama Admin</th>
                <th>Kantor Cabang</th>
                <th>Analisa Kerusakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($shipment->serviceItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->serial_number }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->itemType->type_name }}</td>
                    <td>{{ $item->merk->merk_name }}</td>
                    <td>{{ $item->creator->name }}</td>
                    <td>{{ $item->creator->branchOffice->name }}</td>
                    <td>{{ $item->analisa_kerusakan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

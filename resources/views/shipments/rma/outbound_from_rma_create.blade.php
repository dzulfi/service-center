<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang Servis</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f7f6;
            color: #333;
        }
        .container {
            max-width: 700px;
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
            font-size: 2.2em;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        input[type="text"],
        textarea,
        select {
            width: calc(100% - 20px); /* Adjust for padding */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        button {
            background-color: #3498db;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: auto;
        }
        button:hover {
            background-color: #2980b9;
        }
        .error-message-list {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .error {
            color: #e74c3c;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
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
    @extends('layouts.app') @section('title', 'RMA: Kirim Barang Kembali ke Cabang') @section('content')
        <div class="container">
            <h1>RMA: Kirim Barang Kembali ke Cabang</h1>
            <p>Isi detail pengiriman kembali untuk barang servis <strong>{{ $serviceItem->name }} (SN: {{ $serviceItem->serial_number ?? '-' }})</strong> milik <strong>{{ $serviceItem->customer->name ?? '-' }}</strong>.</p>

            @if ($errors->any())
                <ul class="error-message-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            @if (session('error'))
                <div class="message error-message">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('shipments.rma.outbound_from_rma.store', $serviceItem->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="resi_number">Nomor Resi Pengembalian:</label>
                    <input type="text" name="resi_number" id="resi_number" value="{{ old('resi_number') }}" required>
                    @error('resi_number') <div class="error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="resi_image">Upload Gambar Resi Pengembalian:</label>
                    <input type="file" name="resi_image" id="resi_image" accept="image/*">
                    @error('resi_image') <div class="error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="notes">Catatan Tambahan:</label>
                    <textarea name="notes" id="notes">{{ old('notes') }}</textarea>
                    @error('notes') <div class="error">{{ $message }}</div> @enderror
                </div>
                <button type="submit">Konfirmasi Kirim Balik</button>
            </form>
            <a href="{{ route('shipments.rma.outbound_from_rma.index') }}" class="back-link">Kembali</a>
        </div>
    @endsection
</body>
</html>
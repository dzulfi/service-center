<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang Servis: {{ $serviceItem->item_name }}</title>
    {{-- <style>
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
            width: calc(100% - 20px);
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
    </style> --}}
</head>
<body>
    @extends('layouts.app') @section('title', 'Daftar Mitra Bisnis') @section('content')
        <div class="container">
            <h1>Edit Barang Servis: {{ $serviceItem->name }}</h1>

            @if ($errors->any())
                <ul class="error-message-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form action="{{ route('service_items.update', $serviceItem->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="customer_id">Mitra Bisnis:</label>
                    <select name="customer_id" id="customer_id" required>
                        <option value="">-- Pilih Mitra Bisnis --</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $serviceItem->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->phone_number ?? 'Individu' }})
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="name">Nama Barang:</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $serviceItem->name) }}" required>
                    @error('name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="item_type">Tipe Barang:</label>
                    <select name="item_type_id" id="item_type_id" required>
                        <option value="">-- Tipe Barang --</option>
                        @foreach ($itemTypes as $itemType)
                            <option value="{{ $itemType->id }}" {{ old('item_type_id', $serviceItem->item_type_id) == $itemType->id ? 'selected' : '' }}>
                                {{ $itemType->type_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('item_type_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="serial_number">Serial Number:</label>
                    <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $serviceItem->serial_number) }}">
                    @error('serial_number')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="code">Kode Service</label>
                    <input type="text" name="code" id="code" value="{{ $serviceItem->code }}" readonly>
                </div>
                <div class="form-group">
                    <label for="analisa_kerusakan">Analisa Kerusakan:</label>
                    <textarea name="analisa_kerusakan" id="damage_analysis">{{ old('damage_analysis', $serviceItem->analisa_kerusakan) }}</textarea>
                    @error('analisa_kerusakan')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="jumlah_item">Jumlah Item:</label>
                    <input type="text" name="jumlah_item" id="jumlah_item" value="{{ old('jumlah_item', $serviceItem->jumlah_item) }}">
                    @error('jumlah_item')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit">Update Barang Servis</button>
            </form>
            <a href="{{ route('service_items.index') }}" class="back-link">Kembali ke Daftar Barang Servis</a>
        </div>
    @endsection
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Merk: {{ $itemType->type_name }}</title>
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
        textarea {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,
        textarea:focus {
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
    @extends('layouts.app') @section('title', 'Edit Tipe Barang') @section('content')
        <div class="container">
            <h1>Edit Tipe Barang: {{ $itemType->type_name }}</h1>

            @if ($errors->any())
                <ul class="error-message-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form action="{{ route('item_types.update', $itemType) }}" method="POSt">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="merk_id">Pilih Merk:</label>
                    <select name="merk_id" id="merk_id" required>
                        <option value="">-- Pilih Merk --</option>
                        @foreach ($merks as $merk)
                            <option value="{{ $merk->id }}" {{ old('merk_id', $itemType->merk_id) == $merk->merk_name ? 'selected' : '' }}>
                                {{ $merk->merk_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('merk_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="type_name">Tipe Barang:</label>
                    <input type="text" name="type_name" id="type_name" value="{{ old('type_name', $itemType->type_name) }}" required>
                    @error('type_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit">Update</button>
            </form>
        </div>
    @endsection
</body>
</html>
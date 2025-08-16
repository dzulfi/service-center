<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gunakan Sparepart</title>
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
    </style> --}}
</head>
<body>
    @extends('layouts.app') @section('title', 'Gunakan Sparepart: ') @section('content')
        <div class="container">
            <h1>Sparepart</h1>

            @if ($errors->any())
                <ul class="error-message-list"> {{-- Tampilkan error validasi dengan styling --}}
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            @if (session('success'))
                <div class="message success-message"> {{-- Tampilkan pesan sukses --}}
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="message error-message"> {{-- Tampilkan pesan error --}}
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('stock_out.store', $serviceItem->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="sparepart_id">Pilih Sparepart:</label>
                    <select name="sparepart_id" id="sparepart_id" required>
                        <option value="">-- Pilih Sparepart --</option>
                        @foreach ($spareparts as $sparepart)
                            <option value="{{ $sparepart->id }}" {{ old($sparepart->id) == $sparepart->id ? 'selected' :  '' }}>
                                {{ $sparepart->name }} (stock: {{ $sparepart->getStock() }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity">Jumlah Sparepart:</label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" required min="1">
                    @error('quantity')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="kirim-button">Simpan</button>
            </form>
        </div>
    @endsection
</body>
</html>
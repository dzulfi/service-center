<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang Servis: {{ $sparepart->name }}</title>
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
    </style>
</head>
<body>
    @extends('layouts.app') @section('title', 'Edit Sparepart: ' . $sparepart) @section('content')
        <div class="container">
            <h1>Edit Sparepart : {{ $sparepart->name }}</h1>

            @if (session('success')) 
                <div class="message success-maeesage">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="error-message-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('spareparts.update', $sparepart->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="code">Code Sparepart:</label>
                    <input type="text" name="code" value="{{ old('code', $sparepart->code) }}" required>
                    @error('code')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="name">Nama Sparepart:</label>
                    <input type="text" name="name" value="{{ old('name', $sparepart->name) }}" required>
                    @error('name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="image">Gambar Sparepart:</label>
                    @if ($sparepart->image_path)
                        <img src="{{ Storage::url($sparepart->image_path) }}" alt="{{ $sparepart->name }}" style="width: 100px; height: 100px; object-fit: cover; margin-bottom: 10px;">
                        <p>Gambar saat ini</p>
                    @endif
                    <input type="file" name="image" id="image" accept="image/">
                    @error('image')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="description">Keterangan:</label>
                    <textarea name="description" id="description">{{ old('description', $sparepart->description) }}</textarea>
                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit">Update Sparepart</button>
            </form>
        </div>
    @endsection
</body>
</html>
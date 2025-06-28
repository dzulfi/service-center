<!DOCTYPE html>
<html>
<head>
    <title>Tambah Barang Servis Baru</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], textarea, select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .error { color: red; font-size: 0.9em; }
        .back-link { margin-top: 20px; display: block; }
    </style>
</head>
<body>
    <h1>Tambah Barang Servis Baru</h1>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('service_items.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="customer_id">Pelanggan:</label>
            <select name="customer_id" id="customer_id" required>
                <option value="">-- Pilih Pelanggan --</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }} (Perusahaan: {{ $customer->company ?? 'Individu' }})
                    </option>
                @endforeach
            </select>
            @error('customer_id')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="name">Nama Barang:</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="type">Tipe Barang:</label>
            <input type="text" name="type" id="type" value="{{ old('type') }}">
            @error('type')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="serial_number">Serial Number:</label>
            <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number') }}">
            @error('serial_number')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="analisa_kerusakan">Analisa Kerusakan:</label>
            <textarea name="analisa_kerusakan" id="analisa_kerusakan">{{ old('analisa_kerusakan') }}</textarea>
            @error('analisa_kerusakan')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="merk">Merk:</label>
            <select name="merk" id="merk">
                <option value="">-- Pilih Merk --</option>
                @foreach ($merks as $merk)
                    <option value="{{ $merk }}" {{ old('merk') == $merk ? 'selected' : '' }}>{{ $merk }}</option>
                @endforeach
            </select>
            @error('merk')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="jumlah_item">Jumlah Item:</label>
            <input type="text" name="jumlah_item" id="jumlah_item" value="{{ old('jumlah_item') }}">
            @error('jumlah_item')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit">Simpan Barang Servis</button>
    </form>
    <a href="{{ route('service_items.index') }}" class="back-link">Kembali ke Daftar Barang Servis</a>
</body>
</html>
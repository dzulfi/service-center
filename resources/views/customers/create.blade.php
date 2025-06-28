<!DOCTYPE html>
<html>
<head>
    <title>Tambah Pelanggan Baru</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .error { color: red; font-size: 0.9em; }
        .back-link { margin-top: 20px; display: block; }
    </style>
</head>
<body>
    <h1>Tambah Pelanggan Baru</h1>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nama Pelanggan:</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="phone_number">No. Telepon:</label>
            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}">
            @error('phone_number')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="company">Perusahaan:</label>
            <input type="text" name="company" id="company" value="{{ old('company') }}">
            @error('company')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="address">Alamat:</label>
            <textarea name="address" id="address">{{ old('address') }}</textarea>
            @error('address')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="kelurahan">Kelurahan:</label>
            <input type="text" name="kelurahan" id="kelurahan" value="{{ old('kelurahan') }}">
            @error('kelurahan')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="kecamatan">Kecamatan:</label>
            <input type="text" name="kecamatan" id="kecamatan" value="{{ old('kecamatan') }}">
            @error('kecamatan')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="kota">Kota:</label>
            <input type="text" name="kota" id="kota" value="{{ old('kota') }}">
            @error('kota')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit">Simpan Pelanggan</button>
    </form>
    <a href="{{ route('customers.index') }}" class="back-link">Kembali ke Daftar Pelanggan</a>
</body>
</html>
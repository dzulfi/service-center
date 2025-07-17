@extends('layouts.app') @section('title', 'Tambah Item Barang') @section('content')
    <div class="container">
        <h1>Tambah Item Barang Baru</h1>

        @if ($errors->any())
            <ul class="error-message-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        
        <form action="{{ route('item_types.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="merk_id">Merk Barang:</label>
                <select name="merk_id" id="merk_id" required>
                    <option value="">-- Pilih Merk --</option>
                    @foreach ($merks as $merk)
                        <option value="{{ $merk->id }}" {{ old('merk_id') == $merk->id ? 'selected' : '' }}>
                            {{ $merk->merk_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="type_name">Nama Tipe:</label>
                <input type="text" name="type_name" id="type_name" value="{{ old('type_name') }}" required>
                @error('type_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit">Simpan</button>
        </form>
    </div>
@endsection
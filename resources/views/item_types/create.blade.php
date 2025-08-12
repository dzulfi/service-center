@extends('layouts.app') @section('title', 'Tambah Item Barang') @section('content')
    <div class="container full-width">
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
                <label for="type_name">Nama Tipe:</label>
                <input type="text" name="type_name" id="type_name" value="{{ old('type_name') }}" required>
                @error('type_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="kirim-button">Simpan</button>
        </form>
    </div>
@endsection
@extends('layouts.app') @section('title', 'Tambah Sparepart Baru') @section('content')
    <div class="container full-width">
        <h1>Tambah Sparepart Baru</h1>

        @if ($errors->any())
            <ul class="error-message-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('spareparts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="code">Code Sparepart:</label>
                <input type="text" name="code" id="code" value="{{ old('code') }}" required>
                @error('code') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="name">Nama Sparepart:</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="image">Gambar Sparepart:</label>
                <input type="file" name="image" id="image" accept="image/*">
                @error('image') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="description">Keterangan</label>
                <textarea name="description" id="description">{{ old('description') }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="kirim-button">Simpan</button>
        </form>
    </div>
@endsection
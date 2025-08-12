@extends('layouts.app') @section('title', 'Edit Sparepart: ' . $sparepart) @section('content')
    <div class="container full-width">
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
            <button type="submit" class="kirim-button">Update</button>
        </form>
    </div>
@endsection
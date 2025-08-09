@extends('layouts.app') @section('content')
    <div class="container">
        <h1>Edit Teknisi RMA: {{ $rmaTechnician->name }}</h1>
        
        <form action="{{ route('rma_technicians.update', $rmaTechnician->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" name="name" id="name" value="{{ old('code', $rmaTechnician->name) }}" required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="no_telp">Nomor Telp</label>
                <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp', $rmaTechnician->no_telp) }}" required>
                @error('no_telp')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="kirim-button">Update</button>
        </form>
    </div>
@endsection
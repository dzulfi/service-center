@extends('layouts.app') @section('content')
    <div class="container full-width">
        <h1>Tambah Teknisi RMA Baru</h1>
        
        @if ($errors->any())
            <ul class="error-message-list">
                @foreach ($error->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('rma_technicians.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="no_telp">Nomor Telp</label>
                <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp') }}" required>
                @error('no_telp')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="kirim-button">Simpan</button>
        </form>
    </div>
@endsection
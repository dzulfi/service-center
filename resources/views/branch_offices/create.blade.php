@extends('layouts.app') @section('title', 'Tambah Kantor Cabang Baru') @section('content')
    <div class="container full-width">
        <h1>Tambah Kantor Cabang Baru</h1>

        @if ($errors->any())
            <ul class="error-message-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('branch_offices.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nama Cabang:</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="code">Kode Cabang:</label>
                <input type="text" name="code" id="code" value="{{ old('code') }}" required>
                @error('code')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="address">Alamat:</label>
                <textarea name="address" id="address" required>{{ old('address') }}</textarea>
                @error('address') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="sub_district">Kelurahan:</label>
                <input type="text" name="sub_district" id="sub_district" value="{{ old('sub_district') }}">
                @error('sub_district') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="district">Kecamatan:</label>
                <input type="text" name="district" id="district" value="{{ old('district') }}">
                @error('district') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="city">Kota:</label>
                <input type="text" name="city" id="city" value="{{ old('city') }}" required>
                @error('city') <div class="error">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="kirim-button">Simpan Kantor Cabang</button>
        </form>
        <a href="{{ route('branch_offices.index') }}" class="back-link">Kembali ke Daftar Cabang</a>
    </div>
@endsection
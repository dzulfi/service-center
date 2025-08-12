@extends('layouts.app') @section('title', 'Daftar Mitra Bisnis') @section('content')
    <div class="container full-width">
        <h1>Tambah Mitra Bisnis Baru</h1>

        @if ($errors->any())
            <ul class="error-message-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nama:</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="code">Kode:</label>
                <input type="text" name="code" id="code" value="{{ old('code') }}" required>
                @error('code')
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
            <button type="submit">Simpan</button>
        </form>
        {{-- <a href="{{ route('customers.index') }}" class="back-link">Kembali ke Daftar Mitra Bisnis</a> --}}
    </div>
@endsection
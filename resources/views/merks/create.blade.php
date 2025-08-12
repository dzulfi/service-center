@extends('layouts.app') @section('title','Tambahkan Merk Baru') @section('content')
    <div class="container full-width">
        <h1>Tambah Merk Baru</h1>

        @if ($errors->any())
            <ul class="error-message-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('merks.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="merk_name">Nama Merk:</label>
                <input type="text" name="merk_name" id="merk_name" value="{{ old('merk_name') }}" required>
                @error('merk_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit">Simpan</button>
        </form>
    </div>
@endsection
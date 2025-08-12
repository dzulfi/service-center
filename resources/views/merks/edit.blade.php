@extends('layouts.app') @section('title', 'Edit Merk') @section('content')
    <div class="container full-width">
        <h1>Edit Merk: {{ $merk->merk_name }}</h1>

        @if ($errors->any())
            <ul class="error-message-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('merks.update', $merk->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="merk_name">Nama Merk:</label>
                <input type="text" name="merk_name" id="merk_name" value="{{ old('merk_name', $merk->merk_name) }}" required>
                @error('merk_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit">Update</button>
        </form>
    </div>
@endsection
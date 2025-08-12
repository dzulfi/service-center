@extends('layouts.app') @section('title', 'Edit Tipe Barang') @section('content')
    <div class="container full-width">
        <h1>Edit Tipe Barang: {{ $itemType->type_name }}</h1>

        @if ($errors->any())
            <ul class="error-message-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('item_types.update', $itemType) }}" method="POSt">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="type_name">Tipe Barang:</label>
                <input type="text" name="type_name" id="type_name" value="{{ old('type_name', $itemType->type_name) }}" required>
                @error('type_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit">Update</button>
        </form>
    </div>
@endsection
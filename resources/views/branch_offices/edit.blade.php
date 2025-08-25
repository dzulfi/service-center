@extends('layouts.app') @section('title', 'Edit Kantor Cabang: ' . $branchOffice->name) @section('content')
    <div class="container full-width">
        <h1>Edit Kantor Cabang: {{ $branchOffice->name }}</h1>

        @if ($errors->any())
            <ul class="error-message-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('branch_offices.update', $branchOffice->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nama Cabang:</label>
                <input type="text" name="name" id="name" value="{{ old('name', $branchOffice->name) }}" required>
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="code">Kode Cabang:</label>
                <input type="text" name="code" id="code" value="{{ old('code', $branchOffice->code) }}" required>
                @error('code')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="address">Alamat:</label>
                <textarea name="address" id="address" required>{{ old('address', $branchOffice->address) }}</textarea>
                @error('address') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="sub_district">Kelurahan:</label>
                <input type="text" name="sub_district" id="sub_district" value="{{ old('sub_district', $branchOffice->sub_district) }}">
                @error('sub_district') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="district">Kecamatan:</label>
                <input type="text" name="district" id="district" value="{{ old('district', $branchOffice->district) }}">
                @error('district') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="city">Kota:</label>
                <input type="text" name="city" id="city" value="{{ old('city', $branchOffice->city) }}" required>
                @error('city') <div class="error">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="kirim-button">Update Kantor Cabang</button>
        </form>
        <a href="{{ route('branch_offices.index') }}" class="back-link">Kembali ke Daftar Cabang</a>
    </div>
@endsection
@extends('layouts.app') @section('content')
    <div class="container">
        <h1>Pengembalian Sparepart</h1>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('stock_return.store', $serviceItem->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="sparepart_id" class="form-label">Sparepart</label>
                <select name="sparepart_id" id="sparepart_id" class="form-select" required>
                    <option value="">-- Pilih Sparepart --</option>
                    @foreach($spareparts as $sparepart)
                        <option value="{{ $sparepart->id }}"
                            {{ old('sparepart_id') == $sparepart->id ? 'selected' : '' }}>
                            {{ $sparepart->name }}
                        </option>
                    @endforeach
                </select>
                @error('sparepart_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            @if(isset($maxReturnable))
            <div class="mb-3">
                <p><strong>Maksimal Pengembalian: {{ $maxReturnable }}</strong></p>
            </div>
            @endif

            <div class="mb-3">
                <label for="quantity" class="form-label">Jumlah yang Dikembalikan</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}" min="1" required>
                @error('quantity')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Kembalikan Sparepart</button>
            <a href="{{ route('service_processes.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection

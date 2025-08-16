@extends('layouts.app') @section('title', 'Admin: Kirim Barang ke RMA') @section('content')
    <div class="container">
        <h1>Admin: Kirim Barang ke RMA</h1>
        <p>Isi detail pengiriman untuk barang servis <strong>{{ $serviceItem->name }} (SN: {{ $serviceItem->serial_number ?? '-' }})</strong> milik <strong>{{ $serviceItem->customer->name ?? '-' }}</strong>.</p>

        @if ($errors->any())
            <ul class="error-message-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        @if (session('error'))
            <div class="message error-message">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('shipments.admin.outbound_to_rma.store', $serviceItem->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="resi_number">Nomor Resi:</label>
                <input type="text" name="resi_number" id="resi_number" value="{{ old('resi_number') }}" required>
                @error('resi_number') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="resi_image">Upload Gambar Resi:</label>
                <input type="file" name="resi_image" id="resi_image" accept="image/*">
                @error('resi_image') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="notes">Catatan Tambahan:</label>
                <textarea name="notes" id="notes">{{ old('notes') }}</textarea>
                @error('notes') <div class="error">{{ $message }}</div> @enderror
            </div>
            <button type="submit">Konfirmasi Kirim</button>
        </form>
        <a href="{{ route('shipments.admin.outbound_to_rma.index') }}" class="back-link">Kembali</a>
    </div>
@endsection
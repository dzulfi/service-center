@extends('layouts.app') @section('title', 'Detail Sparepart: ' . $sparepart->name) @section('content')
    <div class="container full-width">
        <h1>Detail Sparepart</h1>

        @if (session('success'))
            <div class="message success-maeesage">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="message error-message">
                {{ session('error') }}
            </div>
        @endif

        <div class="detail-group">
            <strong>Code Sparepart</strong>
            <div class="space">:</div>
            <span>{{ $sparepart->code ?? '-' }}</span>
        </div>
        <div class="detail-group">
            <strong>Name Sparepart</strong>
            <div class="space">:</div>
            <span>{{ $sparepart->name ?? '-' }}</span>
        </div>
        <div class="detail-group">
            <strong>Gambar</strong>
            <div class="space">:</div>
            @if ($sparepart->image_path)
                <img src="{{ Storage::url($sparepart->image_path) }}" alt="{{ $sparepart->name }}" style="max-width: 200px; height: auto; display: block; margin-top: 10px;">
            @else
                <span>Tidak ada gambar</span>
            @endif
        </div>
        <div class="detail-group">
            <strong>Keterangan</strong>
            <div class="space">:</div>
            <span>{{ $sparepart->description ?? '-' }}</span>
        </div>
    </div>
@endsection
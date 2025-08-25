@extends('layouts.app') @section('title', 'Detail Kantor Cabang: ' . $branchOffice->name) @section('content')
    <div class="container full-width">
        <h1>Detail Kantor Cabang</h1>

        <div class="detail-group">
            <strong>Nama Cabang:</strong>
            <div class="space">:</div>
            <span>{{ $branchOffice->name }}</span>
        </div>
        <div class="detail-group">
            <strong>Kode Cabang</strong> 
            <div class="space">:</div>
            <span>{{ $branchOffice->code }}</span>
        </div>
        <div class="detail-group">
            <strong>Alamat:</strong> 
            <div class="space">:</div>
            <span>
                {{ $branchOffice->address }}, {{ $branchOffice->sub_district ?? '-' }}, {{ $branchOffice->district ?? '-' }}, {{ $branchOffice->city }}
            </span>
        </div>

        <div class="actions">
            <a href="{{ route('branch_offices.edit', $branchOffice->id) }}" class="edit-button">Edit Cabang</a>
            <form action="{{ route('branch_offices.destroy', $branchOffice->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-button" onclick="return confirm('Anda yakin ingin menghapus cabang ini?')">Hapus Cabang</button>
            </form>
        </div>

        <a href="{{ route('branch_offices.index') }}" class="back-link">Kembali ke Daftar Cabang</a>
    </div>
@endsection
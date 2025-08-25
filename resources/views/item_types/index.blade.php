@extends('layouts.app') @section('title', 'Daftar Tipe Barang') @section('content')
    <div class="container full-width">
        <h1>Daftar Tipe Barang</h1>

        @if (session('success'))
            <div class="message success-message">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('item_types.create') }}" class="add-button">Tambah Tipe Barang</a>

        <div class="table-responsive">
            <table id="tableItemTypes">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tipe Barang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
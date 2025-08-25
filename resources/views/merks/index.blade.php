@extends('layouts.app') @section('title', 'Daftar Merk') @section('content')
    <div class="container full-width">
        <h1>Daftar Merk</h1>

        @if (session('success'))
            <div class="message success-message">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('merks.create') }}" class="add-button">Tambah Merk</a>

        <div class="table-responsive">
            <table id="tableMerks">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Merk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
@endsection
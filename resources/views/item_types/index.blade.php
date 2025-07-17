@extends('layouts.app') @section('title', 'Daftar Tipe Barang') @section('content')
    <div class="container">
        <h1>Daftar Tipe Barang</h1>

        @if (session('success'))
            <div class="message success-message">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('item_types.create') }}" class="add-button">Tambah Tipe Barang</a>

        @if ($itemTypes->isEmpty())
            <p class="no-data">Belum ada Tipe barang yang terdaftar</p>
        @else
            <table class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Merk</th>
                            <th>Tipe Barang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($itemTypes as $itemType)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $itemType->merk->merk_name }}</td>
                                <td>{{ $itemType->type_name }}</td>
                                <td class="actions">
                                    {{-- <a href="#" class="view-button">Lihat</a> --}}
                                    <a href="{{ route('item_types.edit', $itemType->id) }}" class="edit-button">Edit</a>
                                    <form action="{{ route('item_types.destroy', $itemType->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-button" onclick="return confirm('Anda yakin menghapus tipe barang ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </table>
        @endif
    </div>
@endsection
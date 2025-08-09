@extends('layouts.app') @section('content')
    <div class="container">
        <h1>Daftar Teknisi RMA</h1>

        @if (session('success'))
            <div class="message success-message">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="message error-message">
                {{ session('error') }}
            </div>
        @endif

        <a href="{{ route('rma_technicians.create') }}" class="add-button">Tambah Teknisi</a>
        
        @if ($rmaTechnicians->isEmpty())
            <p class="no-data">Belum ada Teknisi yang terdaftar</p>
        @else
            <div class="table-responsive">
                <table>
                    <tbody>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>No Handphone</th>
                            <th>Aksi</th>
                        </tr>
                    </tbody>
                    <tbody>
                        @foreach ($rmaTechnicians as $rmaTechnician => $teknisi)
                            <tr>
                                <td>{{ $rmaTechnician + 1 }}</td>
                                <td>{{ $teknisi->name }}</td>
                                <td>{{ $teknisi->no_telp }}</td>
                                <td class="actions">
                                    <a href="{{ route('rma_technicians.edit', $teknisi->id) }}" class="edit-button">Edit</a>
                                    <form action="{{ route('rma_technicians.destroy', $teknisi->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-button" onclick="return confirm('Anda Yakin Menghapus Teknisi RMA ini.')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
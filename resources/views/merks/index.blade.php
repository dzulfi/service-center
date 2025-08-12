@extends('layouts.app') @section('title', 'Daftar Merk') @section('content')
    <div class="container full-width">
        <h1>Daftar Merk</h1>

        @if (session('success'))
            <div class="message success-message">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('merks.create') }}" class="add-button">Tambah Merk</a>

        @if ($merks->isEmpty())
            <p class="no-data">Belum ada merk yang terdaftar.</p>
        @else
            <table class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Merk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($merks as $merk)
                            <tr>
                                <td>{{ ($merks->currentPage() - 1) * $merks->perPage() + $loop->iteration }}</td>
                                <td>{{ $merk->merk_name }}</td>
                                <td class="actions">
                                    {{-- <a href="{{ route('merks.show', $merk->id) }}" class="view-button">Lihat</a> --}}
                                    <a href="{{ route('merks.edit', $merk->id) }}" class="edit-button">Edit</a>
                                    <form action="{{ route('merks.destroy', $merk->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-button" onclick="return confirm('Anda yakin menghapus merk ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination-wrapper">
                    {{ $merks->links() }}
                </div>
            </table>
        @endif

    </div>
@endsection
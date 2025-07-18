@extends('layouts.app') @section('title', 'Daftar Sparepart') @section('content')
    <div class="container">
        <h1>Daftar Sparepart</h1>

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

        <a href="{{ route('spareparts.create') }}" class="add-button"> Tambah Sparepart Baru</a>

        @if ($spareparts->isEmpty())
            <p class="no-data">Belum ada Sparepart yang terdaftar.</p>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Code</th>
                            <th>Nama Sparepart</th>
                            <th>Gambar</th>
                            <th>Keterangan</th>
                            <th>Stock</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($spareparts as $sparepart)
                            <tr>
                                {{-- <td>{{ $no++ }}</td> --}}
                                <td>{{ ($spareparts->currentPage() - 1) * $spareparts->perPage() + $loop->iteration }}</td>
                                <td>{{ $sparepart->code }}</td>
                                <td>{{ $sparepart->name }}</td>
                                <td>
                                    @if ($sparepart->image_path)
                                        <img src="{{ Storage::url($sparepart->image_path) }}" alt="{{ $sparepart->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        Tidak ada gambar
                                    @endif
                                </td>
                                <td>{{ Str::limit($sparepart->description, 50) ?? '-' }}</td>
                                <td>{{ $sparepart->getStock() }}</td>
                                <td class="actions">
                                    <a href="{{ route('spareparts.show', $sparepart->id) }}" class="view-button">Lihat</a>
                                    <a href="{{ route('spareparts.edit', $sparepart->id) }}" class="edit-button">Edit</a>
                                    <form action="{{ route('spareparts.destroy', $sparepart->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-button" onclick="return confirm('Anda yakin ingin menghapus Sparepart ini.')">Hapus</button>
                                    </form>
                                    <a href="{{ route('stock_in.create', $sparepart->id) }}" class="add-stock">Tambah Stock</a>
                                    <a href="{{ route('stock_out_minus.create', $sparepart->id) }}" class="minus-stock">Kurangi Stock</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrapper">
                {{ $spareparts->links() }}
            </div>
            @endif
    </div>
@endsection
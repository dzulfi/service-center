@extends('layouts.app') @section('title', 'Detail Merk: ' . $merk->merk_name) @section('content')
    <div class="container full-width">
        <h1>Detail Merk</h1>
        <div class="detail-group">
            <strong>Nama Merk:</strong>
            <span>{{ $merk->merk_name }}</span>
        </div>

        @if ($itemTypes->isEmpty())
            <p class="no-data">Belum ada Tipe Barang untuk merk ini.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tipe Barang</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($itemTypes as $itemType)
                    <tr>
                        <td>{{ ($itemTypes->currentPage() - 1) * $itemTypes->perPage() + $loop->iteration }}</td>
                        <td>{{ $itemType->type_name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination-wrapper">
                {{ $itemTypes->links() }}
            </div>
        @endif
    </div>
@endsection
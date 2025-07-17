@extends('layouts.app') @section('title', 'Detail Merk: ' . $merk->merk_name) @section('content')
    <div class="container">
        <h1>Detail Merk</h1>
        <div class="detail-group">
            <strong>Nama Merk:</strong>
            <span>{{ $merk->merk_name }}</span>
        </div>

        @if ($merk->itemTypes->isEmpty())
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
                    @foreach ($merk->itemTypes as $itemType)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $itemType->type_name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
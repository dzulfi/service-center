@extends('layouts.app') @section('content')
    <div class="container">
        <h2>Daftar Resi Pengiriman Service Ke RMA</h2>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Resi</th>
                        <th>Gambar</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shipments as $shipment => $item)
                        <tr>
                            <td>{{ $shipment + 1 }}</td>
                            <td>{{ $item->resi_number }}</td>
                            <td>Gambar</td>
                            <td>{{ $item->notes }}</td>
                            <td class="actions">
                                <a href="{{ route('shipments.admin.resi_outbound_to_rma.edit', $item->id) }}" class="edit-button">Edit</a>
                                <a href="#" class="download-button">Download</a>
                                <button type="button" class="delete-button">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
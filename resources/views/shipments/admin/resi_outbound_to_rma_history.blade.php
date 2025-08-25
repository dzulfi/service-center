@extends('layouts.app') @section('content')
    <div class="container full-width">
        <h1>History Resi Pengiriman Service Ke RMA</h1>

        {{-- @if ($shipments->isEmpty())
            <p class="no-data">Belum ada Resi Kirim yang terbuat</p>
        @else --}}
            <div class="table-responsive">
                <table id="tableHostoryResiToRma">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Resi</th>
                            <th>Gambar</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    {{-- <tbody>
                        @foreach ($shipments as $shipment => $item)
                            <tr>
                                <td>{{ $shipment + 1 }}</td>
                                <td>{{ $item->resi_number }}</td>
                                <td>
                                    @if ($item->resi_image_path)
                                        <img src="{{ Storage::url($item->resi_image_path) }}" alt="{{ $item->resi_number }}" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        Tidak ada gambar
                                    @endif
                                </td>
                                <td>{{ $item->notes }}</td>
                                <td class="actions">
                                    <a href="{{ route('shipments.admin.history_resi_outbound_to_rma.show', $item->id) }}" class="view-button">Lihat</a>
                                    <a href="{{ route('shipments.admin.resi_outbound_to_rma.pdf', $item->id) }}" target="_blank" class="download-button">Cetak</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody> --}}
                </table>
            </div>
        {{-- @endif --}}

    </div>
@endsection
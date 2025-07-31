@extends('layouts.app') @section('content')
    <div class="container">
        <h1>Daftar Resi Pengiriman Service Kembali Ke Admin Cabang</h1>

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

        @if ($shipments->isEmpty())
            <p class="no-data">Belum ada Resi Kirim yang terbuat</p>
        @else
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
                                <td>-</td>
                                <td>{{ $item->notes }}</td>
                                <td class="actions">
                                    <a href="{{ route('shipments.rma.resi_outbound_from_rma.edit', $item->id) }}" class="edit-button">Edit</a>
                                    <a href="{{ route('shipments.rma.resi_outbound_from_rma.pdf', $item->id) }}" target="_blank" class="download-button">Cetak</a>
                                    <form action="{{ route('shipments.rma.resi_outbound_from_rma.destroy', $item->id) }}" method="POST"onsubmit="return confirm('Yakin ingin menghapus resi ini? Semua service item akan dikembalikan ke RMA.')" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-button">Hapus</button>
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
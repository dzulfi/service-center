@extends('layouts.app') @section('title', 'RMA: Barang Masuk Dari Admin') @section('content')
    <div class="container">
        <h1>RMA: Barang Masuk Dari Admin</h1>

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
            <p class="no-data">Tidak ada barang masuk yang menunggu diterima.</p>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Resi</th>
                            <th>Gambar</th>
                            <th>Keterangan</th>
                            <th>Pengirim</th>
                            <th>Kantor Cabang</th>
                            <th>Tanggal Barang Kirim</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @php
                        $no =1;
                    @endphp
                    <tbody>
                        @foreach ($shipments as $shipment)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $shipment->resi_number ?? '-' }}</td>
                                <td>
                                    @if ($shipment->resi_image_path)
                                        <img src="{{ Storage::url($shipment->resi_image_path) }}" alt="{{ $shipment->resi_number }}" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        Tidak ada gambar
                                    @endif
                                </td>
                                <td>{{ $shipment->notes }}</td>
                                <td>{{ $shipment->responsibleUser->name }}</td>
                                <td>{{ $shipment->responsibleUser->branchOffice->name }}</td>
                                <td>{{ $shipment->created_at->format('d M Y H:i') }}</td>
                                <td class="actions">
                                    <a href="{{ route('shipments.admin.inbound_from_rma.show', $shipment->id) }}" class="view-button">Lihat Detail</a>
                                    <form action="{{ route('shipments.admin.inbound_from_rma.receive', $shipment->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="add-button" style="background-color: #28a745;" onclick="return confirm('Anda yakin ingin menerima barang ini?')">Terima Barang</button>
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
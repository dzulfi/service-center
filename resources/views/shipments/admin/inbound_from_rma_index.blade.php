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
                            <th>ID Pengiriman</th>
                            <th>Barang Servis</th>
                            <th>Nomor Resi</th>
                            <th>Dikirim Oleh</th>
                            <th>Tanggal Kirim</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shipments as $shipment)
                            <tr>
                                <td>{{ $shipment->id }}</td>
                                <td>
                                    {{ $shipment->serviceItem->name ?? '-' }} (SN: {{ $shipment->serviceItem->serial_number ?? '-' }})
                                </td>
                                <td>{{ $shipment->resi_number ?? '-' }}</td>
                                <td>{{ $shipment->responsibleUser->name ?? 'N/A' }}</td>
                                <td>{{ $shipment->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <span class="status-badge status-{{ Str::slug($shipment->status->value ?? '') }}">
                                        {{ $shipment->status->value ?? '-' }}
                                    </span>
                                </td>
                                <td class="actions">
                                    <a href="{{ route('shipments.show', $shipment->id) }}" class="view-button">Lihat Detail</a>
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
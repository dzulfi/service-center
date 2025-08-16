@extends('layouts.app') @section('title', 'Detail Pengiriman: ' . ($shipment->resi_number ?? $shipment->id)) @section('content')
    <div class="container">
        <h1>Detail Pengiriman</h1>

        {{-- <div class="detail-group">
            <strong>ID Pengiriman:</strong> 
            <span>{{ $shipment->id }}</span>
        </div> --}}
        <div class="detail-group">
            <strong>Tipe Pengiriman:</strong> 
            <span>{{ $shipment->shipment_type->value ?? '-' }}</span>
        </div>
        <div class="detail-group">
            <strong>Nomor Resi:</strong> 
            <span>{{ $shipment->resi_number ?? '-' }}</span>
        </div>
        <div class="detail-group">
            <strong>Barang Servis:</strong>
            <span>
                @if ($shipment->serviceItem)
                    <a href="{{ route('service_items.show', $shipment->serviceItem->id) }}">
                        {{ $shipment->serviceItem->item_name }} (SN: {{ $shipment->serviceItem->serial_number ?? '-' }})
                    </a>
                @else
                    <span style="color: #999;">(Barang Servis Tidak Ditemukan)</span>
                @endif
            </span>
        </div>
        <div class="detail-group">
            <strong>Customer:</strong>
            <span>
                @if ($shipment->serviceItem && $shipment->serviceItem->customer)
                    <span>{{ $shipment->serviceItem->customer->name }}</span>
                @else
                    <span style="color: #999;">(Mitra Bisnis Tidak Ditemukan)</span>
                @endif
            </span>
        </div>
        <div class="detail-group">
            <strong>Pengirim:</strong> 
            <span>
                {{ $shipment->responsibleUser->name ?? 'N/A' }} 
                ({{ $shipment->responsibleUser->branchOffice->name }})
            </span>
        </div>
        <div class="detail-group">
            <strong>Gambar Resi:</strong>
            <span>
                @if ($shipment->resi_image_path)
                    <a href="{{ Storage::url($shipment->resi_image_path) }}" target="_blank">
                        Lihat Gambar
                    </a>
                    <img src="{{ Storage::url($shipment->resi_image_path) }}" alt="Gambar Resi" style="max-width: 200px; display: block; margin-top: 10px;">
                @else
                    <span style="color: #999;">Tidak ada gambar resi.</span>
                @endif
            </span>
        </div>
        <div class="detail-group">
            <strong>Status Pengiriman:</strong>
            <span>
                <span class="status-badge status-{{ Str::slug($shipment->status->value ?? '') }}">
                    {{ $shipment->status->value ?? '-' }}
                </span>
            </span>
        </div>
        <div class="detail-group">
            <strong>Catatan:</strong> 
            <span>{{ $shipment->notes ?? '-' }}</span>
        </div>
        <div class="detail-group">
            <strong>Service Masuk:</strong> 
            <span>{{ $shipment->created_at->format('d M Y H:i') }}</span>
        </div>
        <div class="detail-group">
            <strong>Dikirim Pada:</strong> 
            <span>{{ $shipment->updated_at->format('d M Y H:i') }}</span>
        </div>

        <a href="{{ url()->previous() }}" class="back-link">Kembali</a>
    </div>
@endsection
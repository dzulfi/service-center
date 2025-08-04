<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Barang Servis: {{ $serviceItem->item_name }}</title>
    <style>
        .list-disc{
            list-style-type: disc;
        }
    </style>
</head>
<body>
    @extends('layouts.app') @section('title', 'Daftar Mitra Bisnis') @section('content')
        <div class="container">
            <h1>Detail Barang Servis</h1>

            <div class="detail-group">
                <strong>Mitra Bisnis:</strong>
                <span>
                    @if ($serviceItem->customer)
                        <a href="{{ route('customers.show', $serviceItem->customer->id) }}">{{ $serviceItem->customer->name }}</a>
                    @else
                        <span style="color: #999;">(Tidak Ditemukan)</span>
                    @endif
                </span>
            </div>
            <div class="detail-group">
                <strong>Nama Barang:</strong> 
                <span>{{ $serviceItem->name }}</span>
            </div>
            <div class="detail-group">
                <strong>Merk:</strong> 
                <span>{{ $serviceItem->merk->merk_name }}</span>
            </div>
            <div class="detail-group">
                <strong>Tipe Barang:</strong> 
                <span>{{ $serviceItem->itemType->type_name ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Serial Number:</strong> 
                <span>{{ $serviceItem->serial_number ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Kode Service</strong> 
                <span>{{ $serviceItem->code ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Analisa Kerusakan:</strong> 
                <span>{{ $serviceItem->analisa_kerusakan ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Service Masuk:</strong> 
                <span>{{ $serviceItem->created_at->format('d M Y H:i') }}</span>
            </div>


            <div class="detail-group">
                <strong>Kirim ke RMA:</strong>
                {{-- <span>{{ $kirimKeRma ? $kirimKeRma->format('d M Y H:i') : '-' }}</span> --}}
                <span>{{ $serviceItem->kirim_ke_rma?->format('d M Y H:i') ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Diterima RMA</strong>
                {{-- <span>{{ $diterimaRma ? $diterimaRma->format('d M Y H:i') : '-' }}</span> --}}
                <span>{{ $serviceItem->diterima_rma?->format('d M Y H:i') ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <Strong>Mulai Dikerjakan:</Strong>
                {{-- <span>{{ $mulaiDikerjakan ? $mulaiDikerjakan->format('d M Y H:i') : '-' }}</span> --}}
                <span>{{ $serviceItem->mulai_dikerjakan?->format('d M Y H:i') ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Selesai Dikerjakan:</strong>
                {{-- <span>{{ $selesaiDikerjakan ? $selesaiDikerjakan->format('d M Y H:i') : '-' }}</span> --}}
                <span>{{ $serviceItem->selesai_dikerjakan?->format('d M Y H:i') ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Kirim Ke Admin:</strong>
                {{-- <span>{{ $dikirimKembali ? $dikirimKembali->format('d M Y H:i') : '-' }}</span> --}}
                <span>{{ $serviceItem->dikirim_kembali?->format('d M Y H:i') ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Diterima Admin:</strong>
                {{-- <span>{{ $diterimaCabang ? $diterimaCabang->format('d M Y H:i') : '-' }}</span> --}}
                <span>{{ $serviceItem->diterima_cabang?->format('d M Y H:i') ?? '-' }}</span>
            </div>

            @foreach ($serviceItem->serviceProcesses as $item)
                <div class="detail-group">
                    <strong>Kerusakan:</strong>
                    <span>{{ $item->damage_analysis_detail }}</span>
                </div>
                <div class="detail-group">
                    <strong>Solusi:</strong>
                    <span>{{ $item->solution }}</span>
                </div>
                <div class="detail-group">
                    <strong>Progres pengerjaan:</strong>
                    <span>
                        @php
                            $latestProcess = $serviceItem->serviceProcesses->sortByDesc('created_at')->first();
                        @endphp
                        @if ($latestProcess)
                            <span class="status-badge status-{{ Str::slug($latestProcess->process_status) }}">
                                {{ $latestProcess->process_status }}
                            </span>
                        @else
                            <span class="status-badge status-pending">Pending</span>
                        @endif
                    </span>
                </div>
                <div class="detail-group">
                    <strong>Keterangan tambahan:</strong>
                    <span>{{ $item->keterangan }}</span>
                </div>
                <div class="detail-group">
                    <strong>Sparepart</strong>
                    <ul class="list-disc">
                        @foreach ($serviceItem->stockSpareparts->groupBy('sparepart_id') as $sparepartId => $stocks)
                            @php
                                $sparepartName = $stocks->first()->sparepart->name ?? 'Nama tidak ditemukan';
                                $currentStock = $serviceItem->getCurrentStockForSparepart($sparepartId);
                            @endphp
                            @if ($currentStock != 0)
                                <li>{{ $sparepartName }} (stock: {{ $serviceItem->getCurrentStockForSparepart($sparepartId) }})</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endforeach

            {{-- Hanya ditampilkan jika user adalah admin cabang saja --}}
            @if (Auth::user() && Auth::user()->isAdmin())
                <div class="actions">
                    <a href="{{ route('service_items.edit', $serviceItem->id) }}" class="edit-button">Edit Barang Servis</a>
                    <form action="{{ route('service_items.destroy', $serviceItem->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-button" onclick="return confirm('Anda yakin ingin menghapus barang servis ini?')">Hapus Barang Servis</button>
                    </form>
                </div>
                <a href="{{ route('service_items.index') }}" class="back-link">Kembali ke Daftar Barang Servis</a>
            @endif

        </div>
    @endsection
</body>
</html>
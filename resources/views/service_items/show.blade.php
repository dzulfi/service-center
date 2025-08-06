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
                <strong>Kode Service</strong> 
                <span>{{ $serviceItem->code ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Serial Number:</strong> 
                <span>{{ $serviceItem->serial_number ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Nama Barang:</strong> 
                <span>{{ $serviceItem->name }}</span>
            </div>
            <div class="detail-group">
                <strong>Mitra Bisnis:</strong>
                <span>
                    @if ($serviceItem->customer)
                        <a href="{{ route('customers.show', $serviceItem->customer->id) }}" style="background-color: rgb(49, 49, 255); padding: 5px; color: white; border-radius: 8px;">{{ $serviceItem->customer->name }}</a>
                    @else
                        <span style="color: #999;">(Tidak Ditemukan)</span>
                    @endif
                </span>
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
                <strong>Analisa Kerusakan:</strong> 
                <span>{{ $serviceItem->analisa_kerusakan ?? '-' }}</span>
            </div>
            <div class="detail-group">
                <strong>Service Masuk:</strong> 
                <span>{{ $serviceItem->created_at->format('d M Y H:i') }}</span>
            </div>

            <h2>Timeline Barang Service</h2>
            <table>
                <thead>
                    <tr>
                        <th>Service Masuk</th>
                        <th>Kirim ke RMA</th>
                        <th>Diterima RMA</th>
                        <th>Mulai Dikerjakan</th>
                        <th>Selesai Dikerjakan</th>
                        <th>Kirim ke Admin</th>
                        <th>Diterima Admin</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $serviceItem->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $serviceItem->kirim_ke_rma?->format('d M Y H:i') ?? '-' }}</td>
                        <td>{{ $serviceItem->diterima_rma?->format('d M Y H:i') ?? '-' }}</td>
                        <td>{{ $serviceItem->mulai_dikerjakan?->format('d M Y H:i') ?? '-' }}</td>
                        <td>{{ $serviceItem->selesai_dikerjakan?->format('d M Y H:i') ?? '-' }}</td>
                        <td>{{ $serviceItem->dikirim_kembali?->format('d M Y H:i') ?? '-' }}</td>
                        <td>{{ $serviceItem->diterima_cabang?->format('d M Y H:i') ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>

            <h2>Informasi Pengerjaan RMA</h2>
            <table>
                <thead>
                    <tr>
                        <th>Ditangani Oleh</th>
                        <th>Kerusakan</th>
                        <th>Solusi</th>
                        <th>Keterangan</th>
                        <th>Sparepart</th>
                        <th>Status Pengerjaan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($serviceItem->serviceProcesses->sortBy('created_at') as $process)
                        <td>{{ $process->handler->name }}</td>
                        <td>{{ Str::limit($process->damage_analysis_detail ?? '-', 50) }}</td>
                        <td>{{ Str::limit($process->solution ?? '-', 50) }}</td>
                        <td>{{ Str::limit($process->keterangan ?? '-', 50) }}</td>
                        <td>
                            @if ($serviceItem->stockSpareparts->isEmpty())
                                <div style="color: rgb(255, 93, 93); font-weight: bold;">
                                    Tidak memakai sparepart
                                </div>
                            @else
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
                            @endif
                        </td>
                        <td>
                            <span class="status-badge status-{{ Str::slug($process->process_status) }}">
                                {{ $process->process_status }}
                            </span>
                        </td>
                    @endforeach
                </tbody>
            </table>

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
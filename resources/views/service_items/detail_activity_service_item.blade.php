@extends('layouts.app') @section('title', 'Daftar Mitra Bisnis') @section('content')
    <div class="container full-width">
        <h1>Detail Barang Servis</h1>
        
        <div class="detail-group">
            <strong>Kode Service</strong>
            <div class="space">:</div>
            <span>{{ $serviceItem->code }}</span>
        </div>
        <div class="detail-group">
            <strong>Serial Number</strong> 
            <div class="space">:</div>
            <span>{{ $serviceItem->serial_number ?? '-' }}</span>
        </div>
        <div class="detail-group">
            <strong>Nama Barang:</strong> 
            <div class="space">:</div>
            <span>{{ $serviceItem->name }}</span>
        </div>
        <div class="detail-group">
            <strong>Merk</strong> 
            <div class="space">:</div>
            <span>{{ $serviceItem->merk->merk_name ?? '-' }}</span>
        </div>
        <div class="detail-group">
            <strong>Tipe Barang</strong> 
            <div class="space">:</div>
            <span>{{ $serviceItem->itemType->type_name ?? '-' }}</span>
        </div>
        <div class="detail-group">
            <strong>Analisa Kerusakan</strong>
            <div class="space">:</div> 
            <span>{{ $serviceItem->analisa_kerusakan ?? '-' }}</span>
        </div>

        <h2>Informasi Mitra Bisnis</h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>No. Telp</th>
                        <th>Perusahaan</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $serviceItem->customer->code }}</td>
                        <td>
                            @if ($serviceItem->customer)
                                <a href="{{ route('activity.customers.detail_activity_customer', $serviceItem->customer->id) }}" style="background-color: rgb(49, 49, 255); padding: 5px; color: white; border-radius: 8px;">{{ $serviceItem->customer->name }}</a>
                            @else
                                <span style="color: #999;">(Tidak Ditemukan)</span>
                            @endif
                        </td>
                        <td>{{ $serviceItem->customer->phone_number ?? '-' }}</td>
                        <td>{{ $serviceItem->customer->company ?? '-' }}</td>
                        <td>
                            {{ $serviceItem->customer->address }}, {{ $serviceItem->customer->kelurahan }}, {{ $serviceItem->customer->kecamatan }}, {{ $serviceItem->customer->kota }}
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        

        <h2>Timeline Barang Service</h2>
        <div class="table-responsive">
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
        </div>

        <h2>Informasi Pengerjaan RMA</h2>
        <div class="table-responsive">
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
                        </td>
                        <td>
                            <span class="status-badge status-{{ Str::slug($process->process_status) }}">
                                {{ $process->process_status }}
                            </span>
                        </td>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
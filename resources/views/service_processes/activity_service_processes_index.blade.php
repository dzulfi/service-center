<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang Servis</title>
    <style>
        .list-disc{
            list-style-type: disc;
        }
    </style>
</head>
<body>
    @extends('layouts.app') @section('title', 'Aktivitas: Semua Proses Servis') @section('content')
        <div class="container">
            <h1>Aktivitas: Daftar Semua Proses Servis</h1>

            {{-- <div class="filter-menu"> 
                <button class="filter-btn active" data-filter="all">Semua</button>
                <button class="filter-btn" data-filter="selesai">Selesai</button>
                <button class="filter-btn" data-filter="tidak-bisa-diperbaiki">Tidak Bisa Diperbaiki</button>
                <button class="filter-btn" data-filter="proses-pengerjaan">Proses Pengerjaan</button>
            </div> --}}

            @if ($serviceItems->isEmpty())
                <p class="no-data">Belum ada proses servis yang terdaftar.</p>
            @else
                <div class="table-responsive">
                    <table id="serviceProcessesTable"> {{-- Beri ID yang unik --}}
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Barang Servis</th>
                                <th>Tipe</th>
                                <th>Merk</th>
                                <th>Kerusakan</th>
                                <th>Solusi</th>
                                <th>Sparepart</th>
                                <th>Status</th>
                                <th>Ditangani</th>
                                <th>Dikerjakan</th>
                                <th>Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($serviceItems as $serviceItem => $item)
                                <tr>
                                    <td>{{ $serviceItem + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->itemType->type_name }}</td>
                                    <td>{{ $item->merk->merk_name }}</td>
                                    @foreach ($item->serviceProcesses as $process)
                                        <td>{{ $process->damage_analysis_detail ?? '-' }}</td>
                                        <td>{{ $process->solution ?? '-' }}</td>
                                    @endforeach
                                    <td>
                                        @if ($item->stockSpareparts->isEmpty())
                                            <div style="color: rgb(255, 93, 93); font-weight: bold;">
                                                Tidak memakai sparepart
                                            </div>
                                        @else
                                            <ul class="list-disc">
                                                @foreach ($item->stockSpareparts->groupBy('sparepart_id') as $sparepartId => $stocks)
                                                    @php
                                                        $sparepartName = $stocks->first()->sparepart->name ?? 'Nama tidak ditemukan';
                                                        $currentStock = $item->getCurrentStockForSparepart($sparepartId);
                                                    @endphp
                                                    @if ($currentStock != 0)
                                                        <li>{{ $sparepartName }} (stock: {{ $item->getCurrentStockForSparepart($sparepartId) }})</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->latestServiceProcess)
                                            <span class="status-badge status-{{ Str::slug($item->latestServiceProcess->process_status) }}">
                                                {{ $item->latestServiceProcess->process_status }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    @foreach ($item->serviceProcesses as $process)
                                        <td>
                                            {{ $process->handler->name }}
                                        </td>
                                    @endforeach
                                    <td>{{ $item->mulai_dikerjakan?->format('d M Y H:i') ?? '-' }}</td>
                                    <td>{{ $item->selesai_dikerjakan?->format('d M Y H:i') ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endsection
</body>
</html>
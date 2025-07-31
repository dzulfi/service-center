<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .list-disc{
            list-style-type: disc;
        }
    </style>
</head>
<body>
    @extends('layouts.app') @section('title', 'Daftar Mitra Bisnis') @section('content')
        <div class="container">
            <h1>Antrian Barang Service</h1>

            @if (session('success'))
                <div class="message success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if ($serviceItems->isEmpty())
                <p class="no-data">Tidak ada barang servis yang perlu dikerjakan atau sedang dalam proses.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Serial Number</th>
                            <th>Mitra Bisnis</th>
                            <th>Analisa Kerusakan Awal</th>
                            {{-- <th>Dikerjakan oleh</th> --}}
                            <th>Kerusakan</th>
                            <th>Solusi</th>
                            <th>Keterangan</th>
                            <th>Status Terakhir</th>
                            <th>Sparepart</th>
                            <th>Aksi</th>
                            <th>Aksi Sparepart</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach ($serviceItems as $item)
                            <tr>
                                <td>{{ ($serviceItems->currentPage() - 1) * $serviceItems->perPage() + $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->serial_number ?? '-' }}</td>
                                <td>
                                    @if ($item->customer)
                                        <a href="{{ route('customers.show', $item->customer->id) }}">{{ $item->customer->name }}</a>
                                    @else
                                        <span style="color: #999;">(Tidak Ditemukan)</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($item->analisa_kerusakan ?? '-', 50) }}</td>
                                
                                {{-- @foreach ($item->serviceProcesses as $service) --}}
                                    @php
                                        $latestProcess = $item->serviceProcesses->sortByDesc('created_at')->first();
                                    @endphp
                                    @if ($latestProcess)
                                        <td>{{ $latestProcess->damage_analysis_detail ?? '-' }}</td>
                                        <td>{{ $latestProcess->solution ?? '-' }}</td>
                                        <td>{{ $latestProcess->keterangan ?? '-' }}</td>
                                    @else
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    @endif
                                {{-- @endforeach --}}

                                <td>
                                    @php
                                        $latestProcess = $item->serviceProcesses->sortByDesc('created_at')->first();
                                    @endphp
                                    @if ($latestProcess)
                                        <span class="status-badge status-{{ Str::slug($latestProcess->process_status) }}">
                                            {{ $latestProcess->process_status }}
                                        </span>
                                    @else
                                        <span class="status-badge status-pending">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <ul class="list-disc">
                                        @foreach ($item->stockSpareparts->groupBy('sparepart_id') as $sparepartId => $stocks)
                                            @php
                                                $sparepartName = $stocks->first()->sparepart->name ?? 'Nama tidak ditemukan';
                                            @endphp
                                            <li>{{ $sparepartName }} (stock: {{ $item->getCurrentStockForSparepart($sparepartId) }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="actions">
                                    <a href="{{ route('service_processes.work_on', $item->id) }}" class="work-button">Kerjakan</a>
                                </td>
                                <td class="actions">
                                    <a href="{{ route('stock_out.index', $item->id) }}" class="stock-out">Gunakan</a>
                                    <a href="{{ route('stock_return.create', $item->id) }}" class="stock-return">Kembalikan</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination-wrapper">
                    {{ $serviceItems->links() }}
                </div>
            @endif
        </div>
    @endsection
</body>
</html>
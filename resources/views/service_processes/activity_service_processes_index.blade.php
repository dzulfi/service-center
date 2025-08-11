<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>Daftar Barang Servis</title> --}}
    <style>
        .list-disc{
            list-style-type: disc;
        }
        form {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    @extends('layouts.app') @section('title', 'Aktivitas: Semua Proses Servis') @section('content')
        <div class="container">
            <h1>Aktivitas: Daftar Semua Proses Servis</h1>

            @php
                $handlers = $serviceItems
                    ->flatMap(fn($item) => $item->rmaTechnicians->pluck('name'))
                    ->unique()
                    ->sort()
                    ->values();
            @endphp

            <form id="filterForm" class="flex flex-wrap items-start gap-6 my-4">

                <!-- Filter Akun RMA -->
                <div class="flex flex-col">
                    <p class="text-sm font-medium mb-1">Filter Akun RMA:</p>
                    <select id="handler" name="handler" class="border rounded px-3 py-2 min-w-[200px]">
                        <option value="all">Semua</option>
                        @foreach ($handlers as $handlerName)
                            <option value="{{ Str::slug($handlerName) }}">{{ $handlerName }}</option>
                        @endforeach
                        <option value="belum-ditangani">Belum Ditangani</option>
                    </select>
                </div>

                <!-- Filter Status Proses -->
                <div class="flex flex-col">
                    <p class="text-sm font-medium mb-1">Filter Status Proses:</p>
                    <select id="status" name="status" class="border rounded px-3 py-2 min-w-[200px]">
                        <option value="all">Semua</option>
                        <option value="selesai">Selesai</option>
                        <option value="tidak-bisa-diperbaiki">Tidak Bisa Diperbaiki</option>
                        <option value="proses-pengerjaan">Proses Pengerjaan</option>
                    </select>
                </div>

                <!-- Tombol Submit -->
                <div>
                    <button type="submit" style="background-color: blue; padding: 5px; color: white; margin-top: 20px;">
                        Terapkan Filter
                    </button>
                </div>
            </form>

            @if ($serviceItems->isEmpty())
                <p class="no-data">Belum ada proses servis yang terdaftar.</p>
            @else
                <div class="table-responsive">
                    <table id="serviceProcessesTable"> {{-- Beri ID yang unik --}}
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Barang Servis</th>
                                <th>Tipe</th>
                                <th>Merk</th>
                                <th>Kerusakan</th>
                                <th>Solusi</th>
                                <th>Sparepart</th>
                                <th>Status</th>
                                <th>Ditangani</th>
                                <th>Aksi</th>
                                <th>Aksi Sparepart</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($serviceItems as $serviceItem => $item)
                                @php
                                    $latestProcess = $item->serviceProcesses->sortByDesc('created_at')->first();
                                    $status = $latestProcess ? $latestProcess->process_status : 'Pending';
                                    $statusSlug = Str::slug($status);
                                    $filterGroup = '';

                                    if ($status === 'Selesai') {
                                        $filterGroup = 'selesai';
                                    } elseif ($status === 'Batal' || $status === 'Tidak bisa diperbaiki') {
                                        $filterGroup = 'tidak-bisa-diperbaiki';
                                    } else {
                                        $filterGroup = 'proses-pengerjaan';
                                    }

                                    $technicianSlugs = $item->rmaTechnicians->pluck('name')
                                        ->map(fn($name) => Str::slug($name))
                                        ->implode(' ');
                                @endphp
                                <tr 
                                    data-filter-group="{{ $filterGroup }}"
                                    data-handler="{{ $technicianSlugs ?: 'belum-ditangani' }}"
                                    {{-- data-handler="{{ $latestProcess?->handler?->name ? Str::slug($latestProcess->handler->name) : 'belum-ditangani' }}" --}}
                                    {{-- data-handler="{{ $latestProcess?->handler?->name ? Str::slug($latestProcess->handler->name) : 'belum-ditangani' }}" --}}
                                >
                                    <td>{{ $serviceItem + 1 }}</td>
                                    <td>{{ $item->mulai_dikerjakan?->format('d M Y H:i') ?? '-' }}</td>
                                    <td>{{ $item->selesai_dikerjakan?->format('d M Y H:i') ?? '-' }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->itemType->type_name }}</td>
                                    <td>{{ $item->merk->merk_name }}</td>

                                    @forelse ($item->serviceProcesses as $process)
                                        <td>{{ $process->damage_analysis_detail }}</td>
                                        <td>{{ $process->solution }}</td>
                                    @empty
                                        <td>-</td>
                                        <td>-</td>
                                    @endforelse

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
                                        @if ($latestProcess)
                                            <span class="status-badge status-{{ $statusSlug }}">
                                                {{ $status }}
                                            </span>
                                        @else
                                            <span class="status-badge status-pending">Pending</span>
                                        @endif
                                        {{-- @if ($item->latestServiceProcess)
                                            <span class="status-badge status-{{ Str::slug($item->latestServiceProcess->process_status) }}">
                                                {{ $item->latestServiceProcess->process_status }}
                                            </span>
                                        @else
                                            <span class="status-badge status-pending">Pending</span>
                                        @endif --}}
                                    </td>
                                    <td>
                                        @if ($item->rmaTechnicians->isNotEmpty())
                                            {{ $item->rmaTechnicians->pluck('name')->join(', ') }}
                                        @else
                                            <div style="color: rgb(255, 74, 74); font-weight: bold;">Belum ada</div>
                                        @endif
                                    </td>
                                    <td class="actions">
                                        <a href="{{ route('activity.service_processes.change', $item->id) }}" class="work-button">Perubahan</a>
                                    </td>
                                    <td class="actions">
                                        <a href="{{ route('stock_out.index', $item->id) }}" class="stock-out">Gunakan</a>
                                        <a href="{{ route('stock_return.create', $item->id) }}" class="stock-return">Kembalikan</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endsection

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#filterForm').on('submit', function (e) {
                e.preventDefault();

                let selectedStatus = $('#status').val();
                let selectedHandler = $('#handler').val();

                $('#serviceProcessesTable tbody tr').each(function () {
                    let row = $(this);
                    let rowStatus = row.data('filter-group');
                    let rowHandler = row.data('handler');

                    let matchStatus = (selectedStatus === 'all' || rowStatus === selectedStatus);
                    let matchHandler = (selectedHandler === 'all' || rowHandler === selectedHandler);

                    if (matchStatus && matchHandler) {
                        row.show();
                    } else {
                        row.hide();
                    }
                });
            });
        });
    </script>
    {{-- <script>
        $(document).ready(function () {
            let activeStatus = 'all';
            let activeHandler = 'all';

            function filterRows() {
                $('#serviceProcessesTable tbody tr').each(function () {
                    let row = $(this);
                    let rowStatus = row.data('filter-group');
                    let rowHandler = row.data('handler');

                    let matchStatus = (activeStatus === 'all' || rowStatus === activeStatus);
                    let matchHandler = (activeHandler === 'all' || rowHandler === activeHandler);

                    if (matchStatus && matchHandler) {
                        row.show();
                    } else {
                        row.hide();
                    }
                });
            }

            $('.status-filter').on('click', function () {
                $('.status-filter').removeClass('active');
                $(this).addClass('active');
                activeStatus = $(this).data('status');
                filterRows();
            });

            $('.handler-filter').on('click', function () {
                $('.handler-filter').removeClass('active');
                $(this).addClass('active');
                activeHandler = $(this).data('handler');
                filterRows();
            });
        });
    </script> --}}
</body>
</html>
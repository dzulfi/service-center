<style>
    .list-disc{
        list-style-type: disc;
    }
    form {
        margin-bottom: 10px;
    }
</style>

@extends('layouts.app') @section('title', 'Aktivitas: Semua Proses Servis') @section('content')
    <div class="container full-width">
        <h1>Aktivitas: Daftar Semua Proses Servis</h1>
        <br>
        <br>

        @php
            $handlers = $serviceItems
                ->flatMap(fn($item) => $item->rmaTechnicians->pluck('name'))
                ->unique()
                ->sort()
                ->values();
        @endphp

        <form id="filterFormRmaActivity" class="flex flex-wrap items-start gap-6 my-4">
            <!-- Filter Tanggal Mulai -->
            <div class="flex flex-col">
                <p class="text-sm font-medium mb-1">Filter Range Mulai Mengerjakan:</p>
                <input type="text" id="dateRangeMulai" class="border rounded px-3 py-2 min-w-[250px]" style="width: 250px;" placeholder="Pilih rentang tanggal mulai">
            </div>

            <!-- Filter Tanggal Selesai -->
            <div class="flex flex-col">
                <p class="text-sm font-medium mb-1">Filter Range Selesai Mengerjakan:</p>
                <input type="text" id="dateRangeSelesai" class="border rounded px-3 py-2 min-w-[250px]" style="width: 250px;" placeholder="Pilih rentang tanggal selesai">
            </div>

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

        <div class="table-responsive">
            <table id="serviceProcessesTableActivity"> {{-- Beri ID yang unik --}}
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
            </table>
        </div>
    </div>
@endsection

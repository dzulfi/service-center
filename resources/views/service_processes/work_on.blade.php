<style>
    .list-disc{
        list-style-type: disc;
    }
    .sparepart{
        margin-left: 20px;
        margin-bottom: 20px;
    }
</style>
@extends('layouts.app') @section('title', 'Daftar Mitra Bisnis') @section('content')
    <div class="container full-width">
        <h1>
            Kerjakan Servis: 
            <div style="font-weight: bold; display: inline;">
                {{ $serviceItem->name }}
            </div>
        </h1>

        <h2 style="font-weight: bold; font-size: 20px;">Informasi Barang Servis</h2>
        <div class="detail-group">
            <strong>Kode Barang</strong> 
            <div class="space">:</div>
            <span>{{ $serviceItem->code ?? '-' }}</span>
        </div>
        <div class="detail-group">
            <strong>Serial Number</strong> 
            <div class="space">:</div>
            <span>{{ $serviceItem->serial_number ?? '-' }}</span>
        </div>
        <div class="detail-group">
            <strong>Mitra Bisnis</strong>
            <div class="space">:</div>
            <span>
                @if ($serviceItem->customer)
                    <a href="{{ route('customers.show', $serviceItem->customer->id) }}">{{ $serviceItem->customer->name }}</a>
                @else
                    <span style="color: #999;">(Tidak Ditemukan)</span>
                @endif
            </span>
        </div>
        <div class="detail-group">
            <strong>Nama Barang</strong> 
            <div class="space">:</div>
            <span>{{ $serviceItem->name }}</span>
        </div>
        <div class="detail-group">
            <strong>Tipe Barang</strong> 
            <div class="space">:</div>
            <span>{{ $serviceItem->itemType->type_name ?? '-' }}</span>
        </div>
        <div class="detail-group">
            <strong>Merk</strong> 
            <div class="space">:</div>
            <span>{{ $serviceItem->merk->merk_name ?? '-' }}</span>
        </div>
        <div class="detail-group">
            <strong>Analisa Admin</strong> 
            <div class="space">:</div>
            <span>{{ $serviceItem->analisa_kerusakan ?? '-' }}</span>
        </div>
        <div class="detail-group">
            <strong>Status Proses</strong>
            <div class="space">:</div>
            @if ($latestProcess)
                <span>
                    <span class="status-badge status-{{ Str::slug($latestProcess->process_status) }}">
                        {{ $latestProcess->process_status }}
                    </span>
                </span>
            @else
                <span class="status-badge status-pending">Belum ada proses</span>
            @endif
        </div>

        <h2 style="margin-top: 40px;">Form Proses Servis</h2>
        @if ($errors->any())
            <ul class="error-message-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('service_processes.store_work', $serviceItem->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="process_status">Status Proses Pengerjaan:</label>
                <select name="process_status" id="process_status" required>
                    <option value="">-- Pilih Status --</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" {{ old('process_status', $latestProcess->process_status ?? 'Pending') == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
                @error('process_status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="damage_analysis_detail">Analisa Kerusakan Detail:</label>
                <textarea name="damage_analysis_detail" id="damage_analysis_detail">{{ old('damage_analysis_detail', $latestProcess->damage_analysis_detail ?? '') }}</textarea>
                @error('damage_analysis_detail')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="solution">Solusi / Tindakan:</label>
                <textarea name="solution" id="solution">{{ old('solution', $latestProcess->solution ?? '') }}</textarea>
                @error('solution')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan Tambahan:</label>
                <textarea name="keterangan" id="keterangan">{{ old('keterangan', $latestProcess->keterangan ?? '') }}</textarea>
                @error('keterangan')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="rma_technician_id">Teknisi Menangani</label>
                <select name="rma_technician_id" id="rma_technician_id" class="form-control" style="width:100%" required>
                    <option value="">-- Pilih Teknisi --</option>
                    @foreach ($rmaTechnicians as $technician)
                        <option value="{{ $technician->id }}" {{ old('rma_technician_id', $serviceItem->rmaTechnicians->first()->id ?? '') == $technician->id ? 'selected' : '' }}>
                            {{ $technician->name }}
                        </option>
                    @endforeach
                </select>
                @error('rma_technician_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="kirim-button">Simpan Proses Servis</button>
        </form>

        <h2 style="margin-top: 40px;">Penggunaan Sparepart</h2>
        @if ($serviceItem->stockSpareparts->isEmpty())
        <div style="color: rgb(255, 93, 93); font-weight: bold; margin-bottom: 20px;">
            Tidak memakai sparepart
        </div>
        @else
            <div class="sparepart">
                <ul class="list-disc">
                    @foreach ($serviceItem->stockSpareparts->groupBy('sparepart_id') as $sparepartId => $stocks)
                        @php
                            $sparepartName = $stocks->first()->sparepart->name ?? 'Nama tidak ditemukan';
                            $currentStock = $serviceItem->getCurrentStockForSparepart($sparepartId);
                        @endphp
                        @if ($currentStock != 0)
                            <li>
                                {{ $sparepartName }} (stock: {{ $serviceItem->getCurrentStockForSparepart($sparepartId) }})
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif
        <div class="actions">
            <a href="{{ route('stock_out.index', $serviceItem->id) }}" class="stock-out">Gunakan</a>
            <a href="{{ route('stock_return.create', $serviceItem->id) }}" class="stock-return">Kembalikan</a>
        </div>

        <a href="{{ route('service_processes.index') }}" class="back-link">Kembali ke Daftar Barang Servis</a>
        <br>
        {{-- @if ($latestProcess)
            <a href="{{ route('service_processes.show', $latestProcess->id) }}" class="back-link" style="margin-left: 10px;">Lihat Riwayat Proses</a>
        @endif --}}
    </div>
@endsection
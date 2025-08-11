<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    @extends('layouts.app') @section('title', 'Daftar Mitra Bisnis') @section('content')
        <div class="container">
            <h1>Kerjakan Servis: {{ $serviceItem->name }}</h1>

            <h2>Informasi Barang Servis</h2>
            <div class="form-group">
                <strong>Kode Barang:</strong> <span>{{ $serviceItem->code ?? '-' }}</span>
            </div>
            <div class="form-group">
                <strong>Serial Number:</strong> <span>{{ $serviceItem->serial_number ?? '-' }}</span>
            </div>
            <div class="form-group">
                <strong>Mitra Bisnis:</strong>
                <span>
                    @if ($serviceItem->customer)
                        <a href="{{ route('customers.show', $serviceItem->customer->id) }}">{{ $serviceItem->customer->name }}</a>
                    @else
                        <span style="color: #999;">(Tidak Ditemukan)</span>
                    @endif
                </span>
            </div>
            <div class="form-group">
                <strong>Nama Barang:</strong> <span>{{ $serviceItem->name }}</span>
            </div>
            <div class="form-group">
                <strong>Tipe Barang:</strong> <span>{{ $serviceItem->itemType->type_name ?? '-' }}</span>
            </div>
            <div class="form-group">
                <strong>Merk:</strong> <span>{{ $serviceItem->merk->merk_name ?? '-' }}</span>
            </div>
            <div class="form-group">
                <strong>Analisa Kerusakan Awal:</strong> <span>{{ $serviceItem->analisa_kerusakan ?? '-' }}</span>
            </div>
            @if ($latestProcess)
                <div class="form-group">
                    <strong>Status Proses Terakhir:</strong>
                    <span>
                        <span class="status-badge status-{{ Str::slug($latestProcess->process_status) }}">
                            {{ $latestProcess->process_status }}
                        </span>
                    </span>
                </div>
            @else
                <div class="form-group">
                    <strong>Status Proses Terakhir:</strong> <span class="status-badge status-pending">Belum ada proses</span>
                </div>
            @endif

            {{-- @if ($latestProcess)
                <div class="form-group">
                    <strong>Status Proses Terakhir:</strong>
                    <span>
                        <span class="status-badge status-{{ Str::slug($latestProcess->process_status) }}">
                            {{ $latestProcess->process_status }}
                        </span>
                    </span>
                </div>
                <div class="form-group">
                    <strong>Analisa Terakhir:</strong> <span>{{ $latestProcess->damage_analysis_detail ?? '-' }}</span>
                </div>
                <div class="form-group">
                    <strong>Solusi Terakhir:</strong> <span>{{ $latestProcess->solution ?? '-' }}</span>
                </div>
                <div class="form-group">
                    <strong>Keterangan Terakhir:</strong> <span>{{ $latestProcess->keterangan ?? '-' }}</span>
                </div>
                <div class="form-group">
                    <strong>Diupdate Terakhir:</strong> <span>{{ $latestProcess->updated_at->format('d M Y H:i') }}</span>
                </div>
            @else
                <div class="form-group">
                    <strong>Status Proses Terakhir:</strong> <span class="status-badge status-pending">Belum ada proses</span>
                </div>
            @endif --}}


            <h2>Form Proses Servis</h2>
            @if ($errors->any())
                <ul class="error-message-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form action="{{ route('activity.service_processes.store', $serviceItem->id) }}" method="POST">
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

            <a href="{{ route('service_processes.index') }}" class="back-link">Kembali ke Daftar Barang Servis</a>
            <br>
            {{-- @if ($latestProcess)
                <a href="{{ route('service_processes.show', $latestProcess->id) }}" class="back-link" style="margin-left: 10px;">Lihat Riwayat Proses</a>
            @endif --}}
        </div>
    @endsection
</body>
</html>
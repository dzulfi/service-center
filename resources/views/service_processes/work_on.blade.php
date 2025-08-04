<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kerjakan Servis: {{ $serviceItem->item_name }}</title>
    {{-- <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f7f6;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.2em;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .info-group {
            margin-bottom: 15px;
            display: flex;
            align-items: baseline;
        }
        .info-group strong {
            flex: 0 0 180px;
            color: #555;
            font-weight: 600;
        }
        .info-group span, .info-group a {
            flex: 1;
            color: #333;
        }
        .info-group a {
            color: #3498db;
            text-decoration: none;
        }
        .info-group a:hover {
            text-decoration: underline;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        input[type="text"],
        textarea,
        select {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        button {
            background-color: #3498db;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: auto;
        }
        button:hover {
            background-color: #2980b9;
        }
        .error-message-list {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .error {
            color: #e74c3c;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        .back-link:hover {
            color: #2980b9;
            text-decoration: underline;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 0.9em;
            font-weight: bold;
            text-transform: uppercase;
            color: #fff;
        }
        .status-pending { background-color: #ffeb3b; color: #333;}
        .status-diagnosa { background-color: #673ab7; }
        .status-proses-pengerjaan { background-color: #2196f3; }
        .status-menunggu-sparepart { background-color: #ff9800; }
        .status-selesai { background-color: #4caf50; }
        .status-batal { background-color: #f44336; }
    </style> --}}
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
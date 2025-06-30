<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Proses Servis: {{ $serviceProcess->serviceItem->item_name ?? 'N/A' }}</title>
    <style>
        /* CSS yang sama dengan edit pelanggan/barang servis sebelumnya */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f7f6;
            color: #333;
        }
        .container {
            max-width: 700px;
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
            min-height: 80px;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Proses Servis: {{ $serviceProcess->serviceItem->name ?? 'N/A' }}</h1>

        @if ($errors->any())
            <ul class="error-message-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('service_processes.update', $serviceProcess->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="service_item_id">Barang Servis:</label>
                <select name="service_item_id" id="service_item_id" required>
                    <option value="">-- Pilih Barang Servis --</option>
                    @foreach ($serviceItems as $item)
                        <option value="{{ $item->id }}" {{ old('service_item_id', $serviceProcess->service_item_id) == $item->id ? 'selected' : '' }}>
                            {{ $item->name }} (SN: {{ $item->serial_number ?? 'N/A' }}) - Pelanggan: {{ $item->customer->name ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
                @error('service_item_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="damage_analysis_detail">Analisa Kerusakan Detail:</label>
                <textarea name="damage_analysis_detail" id="damage_analysis_detail">{{ old('damage_analysis_detail', $serviceProcess->damage_analysis_detail) }}</textarea>
                @error('damage_analysis_detail')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="solution">Solusi / Tindakan:</label>
                <textarea name="solution" id="solution">{{ old('solution', $serviceProcess->solution) }}</textarea>
                @error('solution')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="process_status">Status Proses Pengerjaan:</label>
                <select name="process_status" id="process_status" required>
                    <option value="">-- Pilih Status --</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" {{ old('process_status', $serviceProcess->process_status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                @error('process_status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan Tambahan:</label>
                <textarea name="keterangan" id="keterangan">{{ old('keterangan', $serviceProcess->keterangan) }}</textarea>
                @error('keterangan')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit">Update Proses Servis</button>
        </form>
        <a href="{{ route('service_processes.index') }}" class="back-link">Kembali ke Daftar Proses Servis</a>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Proses Servis</title>
    <style>
        /* CSS yang sama dengan index pelanggan/barang servis sebelumnya */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f7f6;
            color: #333;
        }
        .container {
            max-width: 1200px;
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
            font-size: 2.5em;
        }
        .message {
            padding: 12px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .add-button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
        }
        .add-button:hover {
            background-color: #2980b9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #e9ecef;
            color: #555;
            font-weight: 600;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .actions {
            white-space: nowrap;
        }
        .actions a, .actions button {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9em;
            margin-right: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .actions a.view-button {
            background-color: #28a745;
            color: white;
        }
        .actions a.view-button:hover {
            background-color: #218838;
        }
        .actions a.edit-button {
            background-color: #ffc107;
            color: #333;
        }
        .actions a.edit-button:hover {
            background-color: #e0a800;
        }
        .actions button.delete-button {
            background-color: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
        }
        .actions button.delete-button:hover {
            background-color: #c82333;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #777;
            font-style: italic;
        }
        .nav-links {
            margin-top: 30px;
            text-align: center;
        }
        .nav-links a {
            color: #3498db;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }
        .nav-links a:hover {
            text-decoration: underline;
        }

        /* Status colors */
        .status-pending { background-color: #ffeb3b; color: #333; } /* Amber */
        .status-diagnosa { background-color: #673ab7; color: #fff; } /* Deep Purple */
        .status-proses { background-color: #2196f3; color: #fff; } /* Blue */
        .status-menunggu { background-color: #ff9800; color: #fff; } /* Orange */
        .status-selesai { background-color: #4caf50; color: #fff; } /* Green */
        .status-batal { background-color: #f44336; color: #fff; } /* Red */
        .status-badge {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 0.8em;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Daftar Proses Servis</h1>

        @if (session('success'))
            <div class="message success-message">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('service_processes.create') }}" class="add-button">Tambah Proses Servis Baru</a>

        @if ($serviceProcesses->isEmpty())
            <p class="no-data">Belum ada proses servis yang terdaftar.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Barang Servis</th>
                        <th>Pelanggan</th>
                        <th>Analisa Kerusakan</th>
                        <th>Solusi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($serviceProcesses as $process)
                        <tr>
                            <td>{{ $process->id }}</td>
                            <td>
                                @if ($process->serviceItem)
                                    <a href="{{ route('service_items.show', $process->serviceItem->id) }}">{{ $process->serviceItem->name }} ({{ $process->serviceItem->serial_number ?? '-' }})</a>
                                @else
                                    <span style="color: #999;">(Barang Servis Tidak Ditemukan)</span>
                                @endif
                            </td>
                            <td>
                                @if ($process->serviceItem && $process->serviceItem->customer)
                                    <a href="{{ route('customers.show', $process->serviceItem->customer->id) }}">{{ $process->serviceItem->customer->name }}</a>
                                @else
                                    <span style="color: #999;">(Pelanggan Tidak Ditemukan)</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($process->damage_analysis_detail ?? '-', 50) }}</td>
                            <td>{{ Str::limit($process->solution ?? '-', 50) }}</td>
                            <td>
                                <span class="status-badge status-{{ Str::slug($process->process_status) }}">
                                    {{ $process->process_status }}
                                </span>
                            </td>
                            <td class="actions">
                                <a href="{{ route('service_processes.show', $process->id) }}" class="view-button">Lihat</a>
                                <a href="{{ route('service_processes.edit', $process->id) }}" class="edit-button">Edit</a>
                                <form action="{{ route('service_processes.destroy', $process->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-button" onclick="return confirm('Anda yakin ingin menghapus proses servis ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="nav-links">
            <a href="{{ route('customers.index') }}">Daftar Pelanggan</a>
            <a href="{{ route('service_items.index') }}">Daftar Barang Servis</a>
        </div>
    </div>
</body>
</html>
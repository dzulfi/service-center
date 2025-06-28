<!DOCTYPE html>
<html lang="en">
<head>
    <title>Daftar Pelanggan</title>
</head>
<body>
    <h1>Daftar Pelanggan</h1>

    @if (session('success'))
        <div style="color: green">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('customers.create') }}">Tambah Pelanggan Baru</a>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pelanggan</th>
                <th>No. Telp</th>
                <th>Perusahaan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->phone_number }}</td>
                    <td>{{ $customer->company }}</td>
                    <td>
                        <a href="{{ route('customers.show', $customer->id) }}">Lihat</a>
                        <a href="{{ route('customers.edit', $customer->id) }}">Edit</a>
                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display: inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Anda yakin ingin menghapus pelanggan ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
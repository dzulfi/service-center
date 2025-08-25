@extends('layouts.app') @section('title', 'Daftar Mitra Bisnis') @section('content')
    <div class="container">
        <h1>Daftar Customer</h1>

        @if (session('success'))
            <div class="message success-message">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('customers.create') }}" class="add-button">Tambah Mitra Bisnis Baru</a>

        @if ($customers->isEmpty())
            <p class="no-data">Belum ada mitra bisnis yang terdaftar.</p>
        @else
            <table id="data-tables">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kode</th>
                        <th>No. Telepon</th>
                        <th>Perusahaan</th>
                        <th>Kota</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $index => $customer)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->code }}</td>
                            <td>{{ $customer->phone_number ?? '-' }}</td>
                            <td>{{ $customer->company ?? '-' }}</td>
                            <td>{{ $customer->kota ?? '-' }}</td>
                            <td class="actions">
                                <a href="{{ route('customers.show', $customer->id) }}" class="view-button">Lihat</a>
                                <a href="{{ route('customers.edit', $customer->id) }}" class="edit-button">Edit</a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-button" onclick="return confirm('Anda yakin ingin menghapus pelanggan ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
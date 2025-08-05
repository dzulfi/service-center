@extends('layouts.app') @section('title', 'Aktivitas: Semua Mitra Bisnis') @section('content')
    <div class="container">
        <h1>Aktivitas: Daftar Mitra Bisnis</h1>

        @if (session('success'))
            <div class="message success-message">
                {{ session('success') }}
            </div>
        @endif

        <table id="customer-activity-data-tables" class="display">
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
        </table>

        {{-- @if ($customers->isEmpty())
            <p class="no-data">Belum ada mitra bisnis yang terdaftar.</p>
        @else
            <div class="table-responsive">
                <table>
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
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->code }}</td>
                                <td>{{ $customer->phone_number ?? '-' }}</td>
                                <td>{{ $customer->company ?? '-' }}</td>
                                <td>{{ $customer->kota ?? '-' }}</td>
                                <td class="actions">
                                    <a href="{{ route('activity.customers.detail_activity_customer', $customer->id) }}" class="view-button">Lihat Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination-wrapper">
                    {{ $customers->links() }}
                </div>
            </div>
        @endif --}}
    </div>
@endsection
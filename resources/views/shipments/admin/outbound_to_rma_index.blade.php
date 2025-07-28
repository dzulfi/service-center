@extends('layouts.app') @section('title', 'Admin: Barang Siap Kirim ke RMA') @section('content')
    <div class="container">
        <h1>Admin: Barang Siap Kirim ke RMA</h1>

        @if (session('success'))
            <div class="message success-message">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="message error-message">
                {{ session('error') }}
            </div>
        @endif

        @if ($serviceItems->isEmpty())
            <p class="no-data">Tidak ada barang yang siap dikirim ke RMA.</p>
        @else
            <div class="table-responsive">
                {{-- <table>
                    <thead>
                        <tr>
                            <th>ID Barang</th>
                            <th>Nama Barang</th>
                            <th>Serial Number</th>
                            <th>Mitra Bisnis</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($serviceItems as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->serial_number ?? '-' }}</td>
                                <td>{{ $item->customer->name ?? '-' }}</td>
                                <td>{{ $item->creator->name ?? 'N/A' }}</td>
                                <td class="actions">
                                    <a href="{{ route('shipments.admin.outbound_to_rma.create', $item->id) }}" class="add-button" style="background-color: #28a745;">Kirim Sekarang</a>
                                    <a href="{{ route('service_items.show', $item->id) }}" class="view-button">Lihat Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table> --}}
                <form action="{{ route('shipments.admin.outbound_to_rma.bulk_create') }}" method="GET">
                    <button type="submit">Kirim Barang Terpilih</button>
                    <table>
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select_all"></th>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Serial Number</th>
                                <th>Mitra Bisnis</th>
                                <th>Dibuat Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($serviceItems as $item)
                                <tr>
                                    <td><input type="checkbox" name="service_item_ids[]" value="{{ $item->id }}"></td>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->serial_number ?? '-' }}</td>
                                    <td>{{ $item->customer->name ?? '-' }}</td>
                                    <td>{{ $item->creator->name ?? 'N/A' }}</td>
                                    <td class="actions">
                                        <a href="{{ route('service_items.show', $item->id) }}" class="view-button">Lihat Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        @endif
    </div>
@endsection
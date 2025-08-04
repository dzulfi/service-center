@extends('layouts.app') @section('title', 'RMA: Barang Siap Kirim Kembali ke Cabang') @section('content')
    <div class="container">
        <h1>RMA: Barang Siap Kirim Kembali ke Cabang</h1>

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
            <p class="no-data">Tidak ada barang yang siap dikirim kembali ke cabang.</p>
        @else
            <div class="table-responsive">
                <form action="{{ route('shipments.rma.outbound_from_rma.bulk_create') }}" method="GET">
                    <button type="submit" class="kirim-button">Kirim Barang Terpilih</button>
                    <table>
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="shipment-from-rma"></th>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Serial Number</th>
                                <th>Nama Barang</th>
                                <th>Tipe</th>
                                <th>Merk</th>
                                <th>Mitra Bisnis</th>
                                <th>Dibuat Oleh</th>
                                <th>Cabang</th>
                                <th>Aksi</th>
                                <th>Status Proses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($serviceItems as $serviceItem => $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="service_item_ids[]" value="{{ $item->id }}" data-branch-id="{{ $item->creator->branchOffice->id }}">
                                    </td>
                                    <td>{{ $serviceItem + 1 }}</td>
                                    <td>{{ $item->code }}</td>
                                    <td>{{ $item->serial_number ?? '-' }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->itemType->type_name }}</td>
                                    <td>{{ $item->merk->merk_name }}</td>
                                    <td>{{ $item->customer->name ?? '-' }}</td>
                                    <td>{{ $item->creator->name ?? 'N/A' }}</td>
                                    <td>{{ $item->creator->branchOffice->name }}</td>
                                    <td>
                                        @if ($item->latestServiceProcess)
                                            <span class="status-badge status-{{ Str::slug($item->latestServiceProcess->process_status) }}">
                                                {{ $item->latestServiceProcess->process_status }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="actions">
                                        {{-- <a href="{{ route('shipments.rma.outbound_from_rma.create', $item->id) }}" class="add-button" style="background-color: #007bff;">Kirim Balik Sekarang</a> --}}
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
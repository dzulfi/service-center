@extends('layouts.app') @section('title', 'Admin: Kirim Barang ke RMA') @section('content')
    <div class="container">
        <form action="{{ route('shipments.admin.outbound_to_rma.bulk_store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="resi_number">Nomor Resi:</label>
                <input type="text" name="resi_number" required>
            </div>
            <div class="form-group">
                <label for="resi_image">Upload Gambar Resi:</label>
                <input type="file" name="resi_image" accept="image/*">
            </div>
            <div class="form-group">
                <label for="notes">Catatan:</label>
                <textarea name="notes"></textarea>
            </div>

            <h3>Daftar Barang</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Code</th>
                            <th>Serial Number</th>
                            <th>Barang Service</th>
                            <th>Merk</th>
                            <th>Tipe</th>
                            <th>Mitra Bisnis</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($serviceItems as $serviceItem => $item)
                            <tr>
                                <td>{{ $serviceItem + 1 }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->serial_number }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->merk->merk_name }}</td>
                                <td>{{ $item->itemType->type_name }}</td>
                                <td>{{ $item->customer->name }}</td>
                            </tr>
                            {{-- Input hidden barang service yang dipilih (id barang yang disimpan) --}}
                            <input type="hidden" name="service_item_ids[]" value="{{ $item->id }}">
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="submit" class="kirim-button">Kirim Semua Barang</button>
        </form>
    </div>
@endsection
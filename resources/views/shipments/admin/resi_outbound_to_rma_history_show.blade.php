@extends('layouts.app') @section('content')
    <div class="container">
        <h1>Detail Resi Pengiriman</h1>

        <div class="form-group">
            <strong>No Resi: </strong>
            <span>{{ $shipment->resi_number }}</span>
            {{-- <input type="text" name="resi_number" value="{{ old('resi_number', $shipment->resi_number) }}" class="form-control" required> --}}
        </div>

        <div class="form-group">
            <strong>Gambar Resi: </strong>
            <span>
                @if ($shipment->resi_image_path)
                    <img src="{{ Storage::url($shipment->resi_image_path) }}" alt="{{ $shipment->resi_number }}" style="width: auto; height: 200px; object-fit: cover;">
                @else
                    Tidak ada gambar
                @endif
            </span>
        </div>

        <div class="form-group">
            <strong>Keterangan: </strong>
            <span>{{ $shipment->notes }}</span>
            {{-- <textarea name="notes" class="form-control">{{ old('notes', $shipment->notes) }}</textarea> --}}
        </div>

        <div class="form-group">
            <strong>Daftar Service Item</strong>
            <div class="table-responsive">
                <table >
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Code</th>
                            <th>Serial Number</th>
                            <th>Name</th>
                            <th>Item Type</th>
                            <th>Merk</th>
                            {{-- <th>Admin</th>
                            <th>Kantor Cabang</th>
                            <th>Status</th> --}}
                            {{-- <th>Notes</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($availableItems as $availableItem => $item)
                            <tr>
                                <td>{{ $availableItem + 1 }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->serial_number ?? 'Service #' . $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->itemType->type_name ?? '-' }}</td>
                                <td>{{ $item->merk->merk_name }}</td>
                                {{-- <td>{{ $item->creator->name }}</td>
                                <td>{{ $item->creator->branchOffice->name }}</td>
                                <td>{{ $item->location_status }}</td> --}}
                                {{-- <td>{{ $item->analisa_kerusakan ?? '-' }}</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
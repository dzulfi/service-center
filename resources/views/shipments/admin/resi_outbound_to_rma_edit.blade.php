@extends('layouts.app') @section('content')
<div class="container">
    <h2>Edit Resi Pengiriman</h2>

    <form method="POST" action="{{ route('shipments.admin.resi_outbound_to_rma.update', $shipment->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>No Resi</label>
            <input type="text" name="resi_number" value="{{ old('resi_number', $shipment->resi_number) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Catatan</label>
            <textarea name="notes" class="form-control">{{ old('notes', $shipment->notes) }}</textarea>
        </div>

        <div class="form-group">
            <label>Ganti Gambar Resi</label>
            <input type="file" name="resi_image" class="form-control">
            @if ($shipment->resi_image_path)
                <p>Gambar saat ini: 
                    {{-- <a href="{{ asset('storage/' . $shipment->resi_image_path) }}" target="_blank">Lihat</a> --}}
                    <img src="{{ Storage::url($shipment->resi_image_path) }}" alt="{{ $shipment->resi_number }}" style="width: auto; height: 150px; object-fit: cover;">
                </p>
            @endif
        </div>

        <div class="form-group">
            <label>Daftar Service Item</label>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Pilih</th>
                            <th>Code Item</th>
                            <th>Serial Number</th>
                            <th>    Name</th>
                            <th>Item Type</th>
                            <th>Merk</th>
                            <th>User Create</th>
                            <th>Branch Office</th>
                            <th>Status</th>
                            {{-- <th>Notes</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($availableItems as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" name="service_item_ids[]" value="{{ $item->id }}"
                                        {{ $shipment->serviceItems->contains($item->id) ? 'checked' : '' }}>
                                </td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->serial_number ?? 'Service #' . $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->itemType->type_name ?? '-' }}</td>
                                <td>{{ $item->merk->merk_name }}</td>
                                <td>{{ $item->creator->name }}</td>
                                <td>{{ $item->creator->branchOffice->name }}</td>
                                <td>{{ $item->location_status }}</td>
                                {{-- <td>{{ $item->analisa_kerusakan ?? '-' }}</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <br>
        <a href="{{ route('shipments.admin.resi_outbound_to_rma.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection

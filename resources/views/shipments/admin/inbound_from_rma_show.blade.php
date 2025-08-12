@extends('layouts.app') @section('content')
    <div class="container full-width">
        <div class="detail-group">
            <strong>No Resi</strong>
            <div class="space">:</div>
            <span>{{ $shipment->resi_number }}</span>
        </div>

        <div class="detail-group">
            <strong>Gambar</strong>
            <div class="space">:</div>
            <span>
                @if ($shipment->resi_image_path)
                    <img src="{{ Storage::url($shipment->resi_image_path) }}" alt="{{ $shipment->resi_number }}" style="width: auto; height: 150px; object-fit: cover;">
                @else
                    Tidak ada gambar
                @endif
            </span>
        </div>

        <div class="detail-group">
            <strong>Keterangan</strong>
            <div class="space">:</div>
            <span>{{ $shipment->notes }}</span>
        </div>

        <div class="form-group">
            <label>Daftar Service Item</label>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Code Item</th>
                            <th>Serial Number</th>
                            <th>Name</th>
                            <th>Item Type</th>
                            <th>Merk</th>
                            <th>User Create</th>
                            <th>Branch Office</th>
                            <th>Status</th>
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
                                <td>{{ $item->creator->name }}</td>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
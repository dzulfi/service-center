@extends('layouts.app') @section('content')
    <div class="container full-width">
        <h1>History Barang Masuk dari RMA</h1>

        <div class="table-responsive">
            <table id="tableHistoryInboundFromRma">
                <thead>
                    <th>No</th>
                    <th>Nomor Resi</th>
                    <th>Gambar</th>
                    <th>Keterangan</th>
                    <th>Pengirim</th>
                    <th>Kantor Cabang</th>
                    <th>Tanggal Kirim</th>
                    <th>Tanggal Diterima</th>
                    <th>Aksi</th>
                </thead>
            </table>
        </div>
    </div>
@endsection
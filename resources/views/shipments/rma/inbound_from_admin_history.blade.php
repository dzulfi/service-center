@extends('layouts.app') @section('content')
    <div class="container full-width">
        <h1>History: Barang Masuk Dari Admin</h1>
        
        <div class="table-responsive">
            <table id="tableHistoryInboundFromAdmin">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Resi</th>
                        <th>Gambar</th>
                        <th>Keterangan</th>
                        <th>Pengirim</th>
                        <th>Kantor Cabang</th>
                        <th>Tanggal Kirim</th>
                        <th>Tanggal Diterima</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
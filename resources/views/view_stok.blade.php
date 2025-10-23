@extends('layout')
@section('content')
<h1>View: Stok Terkini</h1>
<table border="1" cellpadding="6" cellspacing="0" width="100%">
  <thead><tr><th>Barang</th><th>Satuan</th><th>Stok</th><th>Update</th></tr></thead>
  <tbody>
    @foreach($rows as $r)
      <tr>
        <td>{{ $r->nama_barang }}</td>
        <td>{{ $r->nama_satuan }}</td>
        <td>{{ $r->stok_terkini ?? $r->stok_terakhir ?? $r->stock }}</td>
        <td>{{ $r->waktu_update }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection

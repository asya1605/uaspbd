@extends('layout')
@section('content')
<h1>View: Detail Pengadaan</h1>

@if(empty($rows))
  <p>Belum ada data.</p>
@else
<table border="1" cellpadding="6" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>ID Pengadaan</th><th>Tanggal</th><th>Status</th><th>User</th><th>Vendor</th>
      <th>ID Detail</th><th>Barang</th><th>Satuan</th><th>Harga</th><th>Qty</th><th>Sub Total</th>
      <th>Subtotal Nota</th><th>PPN</th><th>Total Nota</th>
    </tr>
  </thead>
  <tbody>
  @foreach($rows as $r)
    <tr>
      <td>{{ $r->idpengadaan }}</td>
      <td>{{ $r->timestamp }}</td>
      <td>{{ $r->status }}</td>
      <td>{{ $r->username }}</td>
      <td>{{ $r->nama_vendor }}</td>
      <td>{{ $r->iddetail_pengadaan }}</td>
      <td>{{ $r->nama_barang }}</td>
      <td>{{ $r->nama_satuan }}</td>
      <td>{{ number_format($r->harga_satuan,2) }}</td>
      <td>{{ $r->jumlah }}</td>
      <td>{{ number_format($r->sub_total,2) }}</td>
      <td>{{ number_format($r->subtotal_nilai,2) }}</td>
      <td>{{ number_format($r->ppn,2) }}</td>
      <td>{{ number_format($r->total_nilai,2) }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
@endif
@endsection

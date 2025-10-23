@extends('layout')
@section('content')
<h1>View: Penjualan Harian</h1>
<table border="1" cellpadding="6" cellspacing="0" width="100%">
  <thead><tr><th>Tanggal</th><th>Transaksi</th><th>Subtotal</th><th>PPN</th><th>Total</th></tr></thead>
  <tbody>
    @foreach($rows as $r)
      <tr>
        <td>{{ $r->tanggal }}</td>
        <td>{{ $r->jumlah_transaksi }}</td>
        <td>{{ number_format($r->total_subtotal,2) }}</td>
        <td>{{ number_format($r->total_ppn,2) }}</td>
        <td>{{ number_format($r->total_nilai,2) }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection

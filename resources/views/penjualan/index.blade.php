@extends('layout')
@section('title','Penjualan')  {{-- JANGAN pakai @endsection untuk title --}}

@section('content')
<h1 class="page-title">Penjualan</h1>

<div class="card">
  <p><a class="btn" href="/penjualan/create">+ Buat Penjualan</a></p>
  <table border="0" width="100%" cellpadding="10" style="border-collapse:collapse;margin-top:10px">
    <thead>
      <tr style="background:#f8fafc">
        <th align="left">ID</th>
        <th align="left">Tanggal</th>
        <th align="left">User</th>
        <th align="left">Subtotal</th>
        <th align="left">PPN</th>
        <th align="left">Total</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $r)
      <tr style="border-top:1px solid #eef2f7">
        <td>{{ $r->idpenjualan }}</td>
        <td>{{ $r->created_at }}</td>
        <td>{{ $r->username }}</td>
        <td>{{ number_format($r->subtotal_nilai,2) }}</td>
        <td>{{ number_format($r->ppn,2) }}</td>
        <td>{{ number_format($r->total_nilai,2) }}</td>
      </tr>
      @empty
      <tr><td colspan="6">Belum ada penjualan.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

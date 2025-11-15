@extends('layouts.master')
@section('title','Detail Penjualan')

@section('content')
<style>
  body, select, input, button { font-family:'Poppins',sans-serif; }
  .page-title { color:#c67c8f;text-align:center;font-size:1.8rem;font-weight:700;margin-bottom:1.5rem; }
  .card { background:#fff;border-radius:16px;box-shadow:0 6px 18px rgba(198,124,143,0.15);border:1px solid #ffd6e3;padding:24px;margin-bottom:28px; }
  table { width:100%;border-collapse:collapse;font-size:14px;border-radius:12px;overflow:hidden; }
  thead { background:linear-gradient(90deg,#fcbad3,#ffb6c1);color:white; }
  th,td { padding:10px;text-align:left; }
  tbody tr:nth-child(even){background:#fff4f7;} tbody tr:hover{background:#ffe8ef;transition:0.2s;}
  .btn-pink{background:linear-gradient(90deg,#f9a8d4,#f472b6);padding:10px 18px;color:#fff;border:none;border-radius:10px;transition:0.2s;}
  .btn-pink:hover{transform:scale(1.05);}
</style>

<h1 class="page-title">💅 Detail Penjualan #{{ $penjualan->idpenjualan }}</h1>

<div class="card">
  <h2 style="color:#c67c8f;">Informasi Penjualan</h2>
  <table>
    <tr><td><b>User</b></td><td>: {{ $penjualan->username }}</td></tr>
    <tr><td><b>Margin</b></td><td>: {{ $penjualan->margin_persen ?? '-' }}%</td></tr>
    <tr><td><b>Subtotal</b></td><td>: Rp {{ number_format($penjualan->subtotal_nilai,0,',','.') }}</td></tr>
    <tr><td><b>PPN</b></td><td>: Rp {{ number_format($penjualan->ppn,0,',','.') }}</td></tr>
    <tr><td><b>Total</b></td><td>: <b>Rp {{ number_format($penjualan->total_nilai,0,',','.') }}</b></td></tr>
    <tr><td><b>Waktu</b></td><td>: {{ $penjualan->created_at }}</td></tr>
  </table>
</div>

<div class="card">
  <h2 style="color:#c67c8f;">Daftar Barang</h2>
  <table>
    <thead>
      <tr>
        <th>ID Detail</th>
        <th>Nama Barang</th>
        <th>Harga Satuan</th>
        <th>Jumlah</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @forelse($details as $d)
      <tr>
        <td>{{ $d->iddetail_penjualan }}</td>
        <td>{{ $d->nama_barang }}</td>
        <td>Rp {{ number_format($d->harga_satuan,0,',','.') }}</td>
        <td>{{ $d->jumlah }}</td>
        <td><b>Rp {{ number_format($d->subtotal,0,',','.') }}</b></td>
      </tr>
      @empty
      <tr><td colspan="5" align="center" style="padding:15px;">Belum ada data barang.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div style="text-align:center;margin-top:20px;">
  <a href="{{ route('penjualan.index') }}" class="btn-pink">← Kembali ke Daftar Penjualan</a>
</div>
@endsection

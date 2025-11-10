@extends('layouts.master')
@section('title','Detail Retur')

@section('content')
<h1 class="page-title" style="color:#be185d;">♻️ Detail Retur #{{ $retur->idretur }}</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif

<div class="card" style="margin-bottom:20px;">
  <table width="100%">
    <tr><td>ID Penerimaan</td><td>: {{ $retur->idpenerimaan }}</td></tr>
    <tr><td>User Input</td><td>: {{ $retur->username }}</td></tr>
    <tr><td>Tanggal</td><td>: {{ $retur->created_at }}</td></tr>
  </table>
</div>

<div class="card">
  <h3>Barang yang Diretur</h3>
  <table>
    <thead style="background:#fce7f3;">
      <tr>
        <th>ID Detail</th>
        <th>Nama Barang</th>
        <th>Jumlah</th>
        <th>Alasan</th>
        <th>Tanggal</th>
      </tr>
    </thead>
    <tbody>
      @forelse($details as $d)
      <tr>
        <td>{{ $d->iddetail_retur }}</td>
        <td>{{ $d->nama_barang }}</td>
        <td>{{ $d->jumlah }}</td>
        <td>{{ $d->alasan }}</td>
        <td>{{ $d->created_at }}</td>
      </tr>
      @empty
      <tr><td colspan="5" align="center">Belum ada barang diretur.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<a href="{{ route('retur.index') }}" class="btn" style="background:#2563eb;margin-top:15px;">← Kembali</a>
@endsection

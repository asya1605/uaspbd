@extends('layouts.master')
@section('title','Kelola Penjualan')

@section('content')
<h1 class="page-title">Daftar Penjualan</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif
@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
    <h2 style="margin:0">Data Penjualan</h2>
    <a href="{{ route('penjualan.create') }}" class="btn" style="background:#2563eb">+ Buat Penjualan</a>
  </div>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Tanggal</th>
        <th>User</th>
        <th>Subtotal</th>
        <th>PPN</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $r)
      <tr>
        <td>{{ $r->idpenjualan }}</td>
        <td>{{ $r->created_at }}</td>
        <td>{{ $r->username }}</td>
        <td>Rp {{ number_format($r->subtotal_nilai,0,',','.') }}</td>
        <td>Rp {{ number_format($r->ppn,0,',','.') }}</td>
        <td><b>Rp {{ number_format($r->total_nilai,0,',','.') }}</b></td>
      </tr>
      @empty
      <tr><td colspan="6" align="center">Belum ada penjualan.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

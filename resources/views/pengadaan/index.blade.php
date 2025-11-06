@extends('layouts.master')
@section('title','Kelola Pengadaan')

@section('content')
<h1 class="page-title">Daftar Pengadaan</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif
@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

<div class="card" style="margin-bottom:20px">
  <div style="display:flex;justify-content:space-between;align-items:center">
    <h2 style="margin:0">Data Pengadaan</h2>
    <a href="{{ route('pengadaan.create') }}" class="btn" style="background:#2563eb">+ Tambah Pengadaan</a>
  </div>
</div>

<div class="card">
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>User Input</th>
        <th>Vendor</th>
        <th>Status</th>
        <th>Subtotal</th>
        <th>PPN</th>
        <th>Total Nilai</th>
        <th>Total Barang</th>
        <th>Waktu</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $r)
      <tr>
        <td>{{ $r->idpengadaan }}</td>
        <td>{{ $r->user_input }}</td>
        <td>{{ $r->nama_vendor }}</td>
        <td>{{ $r->status == '1' ? 'Aktif' : 'Nonaktif' }}</td>
        <td>Rp {{ number_format($r->subtotal_nilai,0,',','.') }}</td>
        <td>Rp {{ number_format($r->ppn,0,',','.') }}</td>
        <td><b>Rp {{ number_format($r->total_nilai,0,',','.') }}</b></td>
        <td>{{ $r->total_barang }}</td>
        <td>{{ $r->timestamp }}</td>
        <td>
          <a href="{{ route('pengadaan.items', $r->idpengadaan) }}" class="btn" style="background:#16a34a">Detail</a>
        </td>
      </tr>
      @empty
        <tr><td colspan="10" align="center">Belum ada data pengadaan.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

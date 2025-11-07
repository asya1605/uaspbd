@extends('layouts.master')
@section('title','Kelola Penerimaan')

@section('content')
<h1 class="page-title" style="color:#be185d;">📦 Daftar Penerimaan Barang</h1>

@if(session('ok'))
  <div class="alert-ok" style="background:#fce7f3;color:#be185d;border-left:5px solid #f472b6;">
    {{ session('ok') }}
  </div>
@endif

<div class="card" style="margin-bottom:20px">
  <div style="display:flex;justify-content:space-between;align-items:center;">
    <h2 style="margin:0;">Data Penerimaan</h2>
    <a href="{{ route('penerimaan.create') }}" class="btn" style="background:#ec4899">+ Tambah Penerimaan</a>
  </div>
</div>

<div class="card">
  <table>
    <thead style="background:#fce7f3;">
      <tr>
        <th>ID</th>
        <th>ID Pengadaan</th>
        <th>User</th>
        <th>Status</th>
        <th>Tanggal</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $r)
      <tr>
        <td>{{ $r->idpenerimaan }}</td>
        <td>{{ $r->idpengadaan }}</td>
        <td>{{ $r->username }}</td>
        <td>{{ $r->status == 1 ? 'Aktif' : 'Nonaktif' }}</td>
        <td>{{ $r->created_at }}</td>
        <td>
          <a href="{{ route('penerimaan.items', $r->idpenerimaan) }}" class="btn" style="background:#10b981;">Detail</a>
        </td>
      </tr>
      @empty
        <tr><td colspan="6" align="center">Belum ada data penerimaan.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

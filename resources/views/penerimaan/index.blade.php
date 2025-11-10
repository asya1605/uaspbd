@extends('layouts.master')
@section('title','Kelola Penerimaan')

@section('content')
<style>
  table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(198,124,143,0.15);
  }
  thead {
    background: linear-gradient(90deg, #fcbad3, #ffb6c1);
    color: white;
  }
  thead th {
    padding: 8px 10px;
    text-align: left;
    font-weight: 600;
    white-space: nowrap;
  }
  tbody tr:nth-child(even) { background: #fff4f7; }
  tbody tr:hover { background: #ffe8ef; transition: 0.2s; }
  tbody td {
    padding: 7px 10px;
    color: #4b2e31;
    vertical-align: middle;
    white-space: nowrap;
  }
  .card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 6px 18px rgba(198,124,143,0.15);
    border: 1px solid #ffd6e3;
    padding: 20px;
    margin-bottom: 25px;
    overflow-x: auto;
  }
  .page-title {
    color: #c67c8f;
    text-align: center;
    font-size: 1.6rem;
    font-weight: 700;
    margin-bottom: 1.2rem;
  }
  .btn {
    border: none;
    border-radius: 8px;
    padding: 6px 12px;
    font-weight: 600;
    color: white;
    cursor: pointer;
    transition: 0.2s ease;
    font-size: 13px;
  }
  .btn:hover { transform: scale(1.05); }
  .btn-add { background: linear-gradient(90deg, #ff99b8, #ffaec9); }
  .btn-detail { background: linear-gradient(90deg, #6ee7b7, #34d399); }
  .alert-ok {
    background: #ffe8ef;
    color: #7a2e3c;
    border-left: 5px solid #f69ab3;
    padding: 8px 14px;
    border-radius: 8px;
    margin-bottom: 10px;
    font-size: 14px;
  }
</style>

<h1 class="page-title">Daftar Penerimaan 📦</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif

<div class="card" style="display:flex;justify-content:space-between;align-items:center;">
  <h2 style="margin:0;color:#c67c8f;font-weight:700;">Data Penerimaan</h2>
  <a href="{{ route('penerimaan.create') }}" class="btn btn-add">+ Tambah Penerimaan</a>
</div>

<div class="card">
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Tanggal</th>
        <th>User</th>
        <th>ID Pengadaan</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $r)
      <tr>
        <td>{{ $r->idpenerimaan }}</td>
        <td>{{ $r->created_at }}</td>
        <td>{{ $r->username }}</td>
        <td>{{ $r->idpengadaan }}</td>
        <td>
          @switch($r->status)
            @case('pending') <span style="color:#ca8a04;font-weight:600;">🟡 Pending</span> @break
            @case('diterima') <span style="color:#16a34a;font-weight:600;">🟢 Diterima</span> @break
            @case('retur_sebagian') <span style="color:#dc2626;font-weight:600;">🔴 Retur Sebagian</span> @break
            @default <span style="color:#6b7280;">⚪ Selesai</span>
          @endswitch
        </td>
        <td>
          <a href="{{ route('penerimaan.items', $r->idpenerimaan) }}" class="btn btn-detail">Detail</a>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" align="center" style="padding:15px;">Belum ada data penerimaan</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

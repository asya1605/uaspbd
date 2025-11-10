@extends('layouts.master')
@section('title','Kelola Pengadaan')

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
    letter-spacing: 0.3px;
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
  }

  .alert-err {
    background: #ffe0e0;
    color: #991b1b;
    border-left: 5px solid #f87171;
    padding: 8px 14px;
    border-radius: 8px;
    margin-bottom: 10px;
  }

  @media (max-width: 1024px) {
    table { font-size: 13px; }
    thead th, tbody td { padding: 6px 8px; }
    .btn { padding: 5px 10px; font-size: 12px; }
  }
</style>

<h1 class="page-title">Daftar Pengadaan 🧾</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif
@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

<div class="card" style="display:flex;justify-content:space-between;align-items:center;">
  <h2 style="margin:0;color:#c67c8f;font-weight:700;">Data Pengadaan</h2>
  <a href="{{ route('pengadaan.create') }}" class="btn btn-add">+ Tambah Pengadaan</a>
</div>

<div class="card">
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>User Input</th>
        <th>Vendor</th>
        <th>Status</th>
        <th>Total Nilai (Belum PPN)</th>
        <th>PPN (10%)</th>
        <th>Subtotal (Setelah PPN)</th>
        <th>Total Barang</th>
        <th>Waktu</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $r)
      <tr>
        <td>{{ $r->idpengadaan }}</td>
        <td>{{ $r->username }}</td>
        <td>{{ $r->nama_vendor }}</td>
        <td>
          @if($r->status == '1')
            <span style="color:#16a34a;font-weight:600;">Aktif</span>
          @else
            <span style="color:#b91c1c;font-weight:600;">Nonaktif</span>
          @endif
        </td>
        <td><b>Rp {{ number_format($r->total_nilai,0,',','.') }}</b></td>
        <td>Rp {{ number_format($r->ppn,0,',','.') }}</td>
        <td>Rp {{ number_format($r->subtotal_nilai,0,',','.') }}</td>
        <td>{{ $r->total_barang ?? 0 }}</td>
        <td>{{ $r->timestamp }}</td>
        <td>
          <a href="{{ route('pengadaan.items', $r->idpengadaan) }}" class="btn btn-detail">Detail</a>
        </td>
      </tr>
      @empty
        <tr><td colspan="10" align="center" style="padding:15px;">Belum ada data pengadaan </td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

@extends('layouts.master')
@section('title', 'Kelola Penerimaan')

@section('content')
<style>
  /* 🌸 Style utama */
  .page-title {
    color: #c67c8f;
    font-weight: 700;
    font-size: 1.8rem;
    margin-bottom: 25px;
  }

  .card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 15px rgba(198,124,143,0.15);
    border: 1px solid #ffd6e3;
    padding: 20px 25px;
    margin-bottom: 25px;
    transition: 0.2s;
  }

  .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(198,124,143,0.2);
  }

  /* 🌷 Tombol */
  .btn {
    display: inline-block;
    padding: 8px 14px;
    border: none;
    border-radius: 10px;
    color: white;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: 0.2s ease;
  }

  .btn:hover { transform: scale(1.05); }

  .btn-add {
    background: linear-gradient(90deg, #ff99b8, #ffaec9);
    box-shadow: 0 3px 8px rgba(252,186,211,0.4);
  }

  .btn-detail {
    background: linear-gradient(90deg, #6ee7b7, #34d399);
    box-shadow: 0 3px 8px rgba(52,211,153,0.4);
  }

  .alert-ok {
    background: #fff0f6;
    color: #7a2e3c;
    border-left: 5px solid #f69ab3;
    padding: 10px 16px;
    border-radius: 8px;
    margin-bottom: 12px;
  }

  .alert-err {
    background: #ffe0e0;
    color: #991b1b;
    border-left: 5px solid #f87171;
    padding: 10px 16px;
    border-radius: 8px;
    margin-bottom: 12px;
  }

  /* 🌸 Tabel */
  table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    border-radius: 12px;
    overflow: hidden;
  }

  thead {
    background: linear-gradient(90deg, #fcbad3, #ffb6c1);
    color: white;
  }

  thead th {
    padding: 12px 10px;
    text-align: center;
    font-weight: 600;
    letter-spacing: 0.3px;
  }

  tbody tr:nth-child(even) {
    background: #fff5f8;
  }

  tbody tr:hover {
    background: #ffe9f0;
    transition: 0.2s ease;
  }

  tbody td {
    padding: 10px;
    text-align: center;
    color: #4b2e31;
    border-bottom: 1px solid #ffe0ec;
  }

  .status-badge {
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 8px;
    color: white;
  }

  .status-accepted { background: #34d399; }
  .status-pending { background: #facc15; color: #4b2e31; }
  .status-rejected { background: #f87171; }

</style>

<h1 class="page-title text-center">📦 Daftar Penerimaan Barang</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif
@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

{{-- 🌸 Header card --}}
<div class="card" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
  <h2 style="margin:0;color:#c67c8f;font-weight:700;">Data Batch Penerimaan (yang sudah diterima)</h2>
  @if($bolehTambah)
    <a href="{{ route('penerimaan.create') }}" class="btn btn-add">➕ Buat Penerimaan Baru</a>
  @endif
</div>

{{-- 🌸 Tabel data --}}
<div class="card">
  <table class="table">
    <thead>
      <tr>
        <th width="70">ID</th>
        <th width="160">Tanggal</th>
        <th>User</th>
        <th>Vendor</th>
        <th>ID Pengadaan</th>
        <th>Status</th>
        <th width="120">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $r)
      <tr>
        <td>#{{ $r->idpenerimaan }}</td>
        <td>{{ \Carbon\Carbon::parse($r->created_at)->format('d M Y H:i') }}</td>
        <td>{{ $r->username }}</td>
        <td>{{ $r->nama_vendor }}</td>
        <td>#{{ $r->idpengadaan }}</td>
        <td>
          <span class="status-badge status-accepted">🟢 Diterima</span>
        </td>
        <td>
          <a href="{{ route('penerimaan.items', $r->idpenerimaan) }}" class="btn btn-detail">Detail</a>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" style="text-align:center;color:#a17c83;padding:15px;">
          Belum ada batch penerimaan 😢
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

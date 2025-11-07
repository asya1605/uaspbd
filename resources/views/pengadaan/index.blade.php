@extends('layouts.master')
@section('title','Kelola Pengadaan')

@section('content')
<style>
  /* 🌸 Table style */
  table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Poppins', sans-serif;
    font-size: 15px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(198,124,143,0.15);
  }

  thead {
    background: linear-gradient(90deg, #fcbad3, #ffb6c1);
    color: white;
  }

  thead th {
    padding: 12px;
    text-align: left;
    font-weight: 600;
    letter-spacing: 0.4px;
  }

  tbody tr:nth-child(even) { background: #fff4f7; }
  tbody tr:hover { background: #ffe8ef; transition: 0.2s; }
  tbody td { padding: 10px 12px; color: #4b2e31; vertical-align: middle; }

  /* 🌸 Card */
  .card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 6px 18px rgba(198,124,143,0.15);
    border: 1px solid #ffd6e3;
    padding: 24px;
    margin-bottom: 28px;
  }

  .page-title {
    color: #c67c8f;
    text-align: center;
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
  }

  /* 🌸 Buttons */
  .btn {
    border: none;
    border-radius: 8px;
    padding: 8px 14px;
    font-weight: 600;
    color: white;
    cursor: pointer;
    transition: 0.2s ease;
  }

  .btn:hover { transform: scale(1.05); }

  .btn-add { background: linear-gradient(90deg, #ff99b8, #ffaec9); }
  .btn-detail { background: linear-gradient(90deg, #6ee7b7, #34d399); }

  /* 🌸 Alerts */
  .alert-ok {
    background: #ffe8ef;
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
</style>

<h1 class="page-title">Daftar Pengadaan 🧾</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif
@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

{{-- 🌷 Header Card --}}
<div class="card" style="display:flex;justify-content:space-between;align-items:center;">
  <h2 style="margin:0;color:#c67c8f;font-weight:700;">Data Pengadaan</h2>
  <a href="{{ route('pengadaan.create') }}" class="btn btn-add">+ Tambah Pengadaan</a>
</div>

{{-- 🌷 Table --}}
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
        <td>
          @if($r->status == '1')
            <span style="color:#16a34a;font-weight:600;">Aktif</span>
          @else
            <span style="color:#b91c1c;font-weight:600;">Nonaktif</span>
          @endif
        </td>
        <td>Rp {{ number_format($r->subtotal_nilai,0,',','.') }}</td>
        <td>Rp {{ number_format($r->ppn,0,',','.') }}</td>
        <td><b>Rp {{ number_format($r->total_nilai,0,',','.') }}</b></td>
        <td>{{ $r->total_barang }}</td>
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

@extends('layouts.master')
@section('title','Kelola Penjualan')

@section('content')
<style>
  /* 🌸 General */
  body, select, input, button {
    font-family: 'Poppins', sans-serif;
  }

  .page-title {
    color: #c67c8f;
    text-align: center;
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
  }

  .card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 6px 18px rgba(198,124,143,0.15);
    border: 1px solid #ffd6e3;
    padding: 24px;
    margin-bottom: 28px;
  }

  h2 {
    color: #c67c8f;
    font-size: 1.1rem;
    font-weight: 700;
  }

  /* 🌸 Buttons */
  .btn {
    border: none;
    border-radius: 8px;
    padding: 10px 18px;
    font-weight: 600;
    color: white;
    cursor: pointer;
    transition: 0.2s ease;
  }

  .btn:hover { transform: scale(1.05); }

  .btn-add {
    background: linear-gradient(90deg, #93c5fd, #60a5fa);
  }

  /* 🌸 Table */
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

  /* 🌸 Total emphasis */
  .total-cell {
    font-weight: 700;
    color: #b91c1c;
  }
</style>

<h1 class="page-title">Daftar Penjualan 💄</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif
@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

{{-- 🌷 Header --}}
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
    <h2 style="margin:0;">Data Penjualan</h2>
    <a href="{{ route('penjualan.create') }}" class="btn btn-add">+ Buat Penjualan</a>
  </div>

  {{-- 🌷 Table --}}
<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Tanggal</th>
      <th>User</th>
      <th>Margin (%)</th>
      <th>Subtotal</th>
      <th>PPN</th>
      <th>Total</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($rows as $r)
    <tr>
      <td>{{ $r->idpenjualan }}</td>
      <td>{{ $r->created_at }}</td>
      <td>{{ $r->username }}</td>
      <td>{{ $r->margin_persen ?? '-' }}%</td>
      <td>Rp {{ number_format($r->subtotal_nilai,0,',','.') }}</td>
      <td>Rp {{ number_format($r->ppn,0,',','.') }}</td>
      <td class="total-cell">Rp {{ number_format($r->total_nilai,0,',','.') }}</td>
      <td>
        <a href="{{ route('penjualan.items', $r->idpenjualan) }}" 
           class="btn" 
           style="background:linear-gradient(90deg,#f9a8d4,#f472b6);padding:6px 10px;border-radius:8px;">
          👁️ Detail
        </a>
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="8" align="center" style="padding:15px;">Belum ada penjualan 😢</td>
    </tr>
    @endforelse
  </tbody>
</table>
</div>
@endsection

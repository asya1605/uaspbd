@extends('layouts.master')
@section('title','Kelola Kartu Stok')

@section('content')
<style>
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

  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(198,124,143,0.1);
  }

  thead {
    background: linear-gradient(90deg, #fcbad3, #ffb6c1);
    color: white;
  }
  thead th {
    padding: 10px;
    text-align: left;
    font-weight: 600;
  }

  tbody tr:nth-child(even) { background: #fff4f7; }
  tbody tr:hover { background: #ffe8ef; transition: 0.2s; }
  tbody td { padding: 8px 10px; color: #4b2e31; }

  .btn {
    border: none;
    border-radius: 10px;
    padding: 8px 14px;
    font-weight: 600;
    color: white;
    cursor: pointer;
    transition: 0.2s;
  }
  .btn:hover { transform: scale(1.05); }
  .btn-blue { background: linear-gradient(90deg, #ffbad7ff, #f679beff); }

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

<h1 class="page-title">📦 Daftar Kartu Stok</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif
@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

<div class="card">
  <h2 style="color:#c67c8f;font-size:1.1rem;font-weight:700;margin-bottom:16px;">📋 Data Barang & Stok Terakhir</h2>

  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Nama Barang</th>
        <th>Stok Terakhir</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $r)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $r->nama }}</td>
        <td>{{ $r->stok_terakhir }}</td>
        <td>
          <a href="{{ route('kartustok.history', $r->idbarang) }}" class="btn btn-blue">🔍 History</a>
        </td>
      </tr>
      @empty
      <tr><td colspan="4" align="center" style="padding:12px;">Belum ada data stok 😢</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

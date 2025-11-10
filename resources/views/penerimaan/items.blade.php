@extends('layouts.master')
@section('title', 'Detail Penerimaan')

@section('content')
<style>
  .page-title {
    color: #c67c8f;
    font-weight: 700;
    font-size: 1.6rem;
    text-align: center;
    margin-bottom: 25px;
  }

  .card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(198,124,143,0.12);
    border: 1px solid #ffd6e3;
    padding: 24px 30px;
    margin-bottom: 28px;
    transition: 0.2s;
  }

  .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(198,124,143,0.2);
  }

  table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 12px;
    overflow: hidden;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
  }

  thead {
    background: linear-gradient(90deg, #fcbad3, #ffb6c1);
    color: #fff;
  }

  thead th {
    padding: 10px;
    text-align: center;
    font-weight: 600;
    letter-spacing: 0.4px;
  }

  tbody td {
    padding: 10px;
    border-bottom: 1px solid #ffe4ec;
    color: #4b2e31;
    text-align: center;
  }

  tbody tr:nth-child(even) { background: #fff7f9; }
  tbody tr:hover { background: #fff0f5; transition: 0.2s; }

  .btn {
    padding: 7px 12px;
    border-radius: 8px;
    font-weight: 600;
    color: white;
    border: none;
    cursor: pointer;
    transition: 0.2s;
  }

  .btn:hover { transform: scale(1.05); }
  .btn-pink { background: #ec4899; }
  .btn-red { background: #f87171; }
  .btn-blue { background: #2563eb; }
  .btn-green { background: #16a34a; }

  .alert-ok {
    background: #fdf2f8;
    color: #be185d;
    border-left: 5px solid #f9a8d4;
    padding: 10px 16px;
    border-radius: 8px;
    margin-bottom: 12px;
  }

  .alert-err {
    background: #fee2e2;
    color: #b91c1c;
    border-left: 5px solid #f87171;
    padding: 10px 16px;
    border-radius: 8px;
    margin-bottom: 12px;
  }
</style>

<h1 class="page-title">📦 Detail Penerimaan #{{ $penerimaan->idpenerimaan }}</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif
@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

{{-- 🌸 INFO UMUM --}}
<div class="card">
  <table>
    <tbody>
      <tr>
        <td width="180"><b>ID Pengadaan</b></td>
        <td>{{ $penerimaan->idpengadaan }}</td>
      </tr>
      <tr>
        <td><b>User Input</b></td>
        <td>{{ $penerimaan->username }}</td>
      </tr>
      <tr>
        <td><b>Status</b></td>
        <td>
          @switch($penerimaan->status)
            @case('pending') <span style="color:#ca8a04;font-weight:600;">🟡 Pending</span> @break
            @case('diterima') <span style="color:#16a34a;font-weight:600;">🟢 Diterima</span> @break
            @default <span style="color:#6b7280;">⚪ Selesai</span>
          @endswitch
        </td>
      </tr>
      <tr>
        <td><b>Tanggal</b></td>
        <td>{{ $penerimaan->created_at }}</td>
      </tr>
    </tbody>
  </table>
</div>

{{-- 🌸 RINGKASAN BARANG --}}
<div class="card">
  <h3 style="margin-bottom:12px;color:#b44a6b;font-weight:600;">Ringkasan Barang dari Pengadaan</h3>
  <table>
    <thead>
      <tr>
        <th>Nama Barang</th>
        <th>Satuan</th>
        <th>Dipesan</th>
        <th>Diterima</th>
        <th>Sisa</th>
        <th>Input Diterima</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($ringkasan as $r)
      <tr>
        <td>{{ $r->nama_barang }}</td>
        <td>{{ $r->nama_satuan }}</td>
        <td>{{ $r->total_dipesan }}</td>
        <td>{{ $r->total_diterima }}</td>
        <td>{{ $r->sisa }}</td>
        <td>
          <form action="{{ route('penerimaan.addItem', $penerimaan->idpenerimaan) }}" method="POST" style="display:flex;justify-content:center;align-items:center;gap:6px;">
            @csrf
            <input type="hidden" name="idbarang" value="{{ $r->idbarang }}">
            <input type="number" name="jumlah_terima" value="{{ $r->sisa }}" min="1" max="{{ $r->sisa }}" 
              style="width:70px;border:1px solid #f9a8d4;border-radius:8px;padding:4px;text-align:center;">
            <button type="submit" class="btn btn-pink">Terima</button>
          </form>
        </td>
        <td><a href="#" class="btn btn-red">Return</a></td>
      </tr>
      @empty
      <tr><td colspan="7">Tidak ada barang dari pengadaan ini.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- 🌸 BARANG DITERIMA --}}
<div class="card">
  <h3 style="margin-bottom:12px;color:#b44a6b;font-weight:600;">Barang yang Diterima</h3>
  <table>
    <thead>
      <tr>
        <th>ID Detail</th>
        <th>Nama Barang</th>
        <th>Satuan</th>
        <th>Jumlah</th>
        <th>Harga Satuan</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @forelse($details as $d)
      <tr>
        <td>{{ $d->iddetail_penerimaan }}</td>
        <td>{{ $d->nama_barang }}</td>
        <td>{{ $d->nama_satuan }}</td>
        <td>{{ $d->jumlah_terima }}</td>
        <td>Rp{{ number_format($d->harga_satuan_terima,0,',','.') }}</td>
        <td><b>Rp{{ number_format($d->sub_total_terima,0,',','.') }}</b></td>
      </tr>
      @empty
      <tr><td colspan="6">Belum ada barang diterima.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- 🌸 KONFIRMASI --}}
@if($penerimaan->status == 'pending')
  <div class="card text-center">
    <form action="{{ route('penerimaan.confirm', $penerimaan->idpenerimaan) }}" method="POST">
      @csrf
      <button type="submit" class="btn btn-green w-full">✅ Konfirmasi Semua Barang Diterima</button>
    </form>
  </div>
@endif

<div class="text-center">
  <a href="{{ route('penerimaan.index') }}" class="btn btn-blue">← Kembali</a>
</div>
@endsection

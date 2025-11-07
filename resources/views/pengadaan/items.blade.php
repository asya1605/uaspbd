@extends('layouts.master')
@section('title','Detail Pengadaan')

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
    margin-bottom: 14px;
  }

  label {
    font-weight: 600;
    color: #7b3a4b;
    display: block;
    margin-bottom: 6px;
  }

  input, select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #fbc4d8;
    border-radius: 8px;
    background: #fff9fa;
    transition: 0.2s ease;
  }

  input:focus, select:focus {
    outline: none;
    border-color: #ff8fb2;
    box-shadow: 0 0 6px #ffc1d4;
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
    background: linear-gradient(90deg, #6ee7b7, #34d399);
  }

  .btn-back {
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
</style>

<h1 class="page-title">Detail Pengadaan #{{ $pengadaan->idpengadaan }} 🧾</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif
@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

{{-- 🌷 Informasi Pengadaan --}}
<div class="card">
  <h2>Informasi Pengadaan</h2>
  <table width="100%" cellpadding="6">
    <tr><td width="180"><b>ID Pengadaan</b></td><td>: {{ $pengadaan->idpengadaan }}</td></tr>
    <tr><td><b>User Input</b></td><td>: {{ $pengadaan->user_input }}</td></tr>
    <tr><td><b>Vendor</b></td><td>: {{ $pengadaan->nama_vendor }}</td></tr>
    <tr><td><b>Status</b></td>
      <td>:
        @if($pengadaan->status == '1')
          <span style="color:#16a34a;font-weight:600;">Aktif</span>
        @else
          <span style="color:#b91c1c;font-weight:600;">Nonaktif</span>
        @endif
      </td>
    </tr>
    <tr><td><b>Subtotal</b></td><td>: Rp {{ number_format($pengadaan->subtotal_nilai,0,',','.') }}</td></tr>
    <tr><td><b>PPN</b></td><td>: Rp {{ number_format($pengadaan->ppn,0,',','.') }}</td></tr>
    <tr><td><b>Total</b></td><td>: <b>Rp {{ number_format($pengadaan->total_nilai,0,',','.') }}</b></td></tr>
    <tr><td><b>Waktu</b></td><td>: {{ $pengadaan->timestamp }}</td></tr>
  </table>
</div>

{{-- 🌷 Form Tambah Barang --}}
<div class="card">
  <h2>Tambah Barang ke Pengadaan</h2>
  <form method="POST" action="{{ route('pengadaan.addItem', $pengadaan->idpengadaan) }}">
    @csrf

    <div style="margin-bottom:16px">
      <label>Nama Barang</label>
      <select name="idbarang" required>
        <option value="">-- Pilih Barang --</option>
        @foreach($barangList as $b)
          <option value="{{ $b->idbarang }}">{{ $b->nama }}</option>
        @endforeach
      </select>
    </div>

    <div style="margin-bottom:16px">
      <label>Harga Satuan</label>
      <input type="number" name="harga_satuan" required placeholder="Masukkan harga barang">
    </div>

    <div style="margin-bottom:20px">
      <label>Jumlah</label>
      <input type="number" name="jumlah" required placeholder="Masukkan jumlah barang">
    </div>

    <button class="btn btn-add" type="submit">+ Tambah Barang</button>
  </form>
</div>

{{-- 🌷 Daftar Barang --}}
<div class="card">
  <h2>Daftar Barang Pengadaan Ini</h2>
  <table>
    <thead>
      <tr>
        <th>ID Detail</th>
        <th>Nama Barang</th>
        <th>Harga Satuan</th>
        <th>Jumlah</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @forelse($details as $d)
      <tr>
        <td>{{ $d->iddetail_pengadaan }}</td>
        <td>{{ $d->nama_barang }}</td>
        <td>Rp {{ number_format($d->harga_satuan,0,',','.') }}</td>
        <td align="center">{{ $d->jumlah }}</td>
        <td><b>Rp {{ number_format($d->sub_total,0,',','.') }}</b></td>
      </tr>
      @empty
        <tr><td colspan="5" align="center" style="padding:15px;">Belum ada barang ditambahkan 😢</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div style="margin-top:24px;text-align:center">
  <a href="{{ route('pengadaan.index') }}" class="btn btn-back">← Kembali ke Daftar Pengadaan</a>
</div>
@endsection

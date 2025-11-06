@extends('layouts.master')
@section('title','Detail Pengadaan')

@section('content')
<h1 class="page-title">Detail Pengadaan #{{ $pengadaan->idpengadaan }}</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif
@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

{{-- Informasi Pengadaan --}}
<div class="card" style="margin-bottom:20px">
  <h2>Informasi Pengadaan</h2>
  <table width="100%" cellpadding="6">
    <tr><td width="150">ID Pengadaan</td><td>: {{ $pengadaan->idpengadaan }}</td></tr>
    <tr><td>User Input</td><td>: {{ $pengadaan->user_input }}</td></tr>
    <tr><td>Vendor</td><td>: {{ $pengadaan->nama_vendor }}</td></tr>
    <tr><td>Status</td><td>: {{ $pengadaan->status == '1' ? 'Aktif' : 'Nonaktif' }}</td></tr>
    <tr><td>Subtotal</td><td>: Rp {{ number_format($pengadaan->subtotal_nilai,0,',','.') }}</td></tr>
    <tr><td>PPN</td><td>: Rp {{ number_format($pengadaan->ppn,0,',','.') }}</td></tr>
    <tr><td><b>Total</b></td><td>: <b>Rp {{ number_format($pengadaan->total_nilai,0,',','.') }}</b></td></tr>
    <tr><td>Waktu</td><td>: {{ $pengadaan->timestamp }}</td></tr>
  </table>
</div>

{{-- Form Tambah Barang --}}
<div class="card" style="margin-bottom:20px">
  <h2>Tambah Barang ke Pengadaan</h2>
  <form method="POST" action="{{ route('pengadaan.addItem', $pengadaan->idpengadaan) }}">
    @csrf
    <div style="margin-bottom:10px">
      <label>Nama Barang</label><br>
      <select name="idbarang" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px">
        <option value="">-- Pilih Barang --</option>
        @foreach($barangList as $b)
          <option value="{{ $b->idbarang }}">{{ $b->nama }}</option>
        @endforeach
      </select>
    </div>

    <div style="margin-bottom:10px">
      <label>Harga Satuan</label><br>
      <input type="number" name="harga_satuan" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px">
    </div>

    <div style="margin-bottom:10px">
      <label>Jumlah</label><br>
      <input type="number" name="jumlah" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px">
    </div>

    <button class="btn" style="background:#16a34a">Tambah Barang</button>
  </form>
</div>

{{-- Daftar Barang --}}
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
        <tr><td colspan="5" align="center">Belum ada barang ditambahkan.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div style="margin-top:20px">
  <a href="{{ route('pengadaan.index') }}" class="btn" style="background:#2563eb">← Kembali ke Daftar Pengadaan</a>
</div>
@endsection

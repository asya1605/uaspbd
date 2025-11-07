@extends('layouts.master')
@section('title','Detail Penerimaan')

@section('content')
<h1 class="page-title" style="color:#be185d;">📦 Detail Penerimaan #{{ $penerimaan->idpenerimaan }}</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif
@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

{{-- Informasi utama --}}
<div class="card" style="margin-bottom:20px;">
  <table width="100%" cellpadding="6">
    <tr><td width="180">ID Pengadaan</td><td>: {{ $penerimaan->idpengadaan }}</td></tr>
    <tr><td>User Input</td><td>: {{ $penerimaan->username }}</td></tr>
    <tr><td>Status</td><td>: {{ $penerimaan->status == 1 ? 'Aktif' : 'Nonaktif' }}</td></tr>
    <tr><td>Tanggal</td><td>: {{ $penerimaan->created_at }}</td></tr>
  </table>
</div>

{{-- Form tambah barang --}}
<div class="card" style="margin-bottom:20px;">
  <h3 style="margin-bottom:10px;color:#be185d;">Tambah Barang ke Penerimaan</h3>
  <form method="POST" action="{{ route('penerimaan.addItem', $penerimaan->idpenerimaan) }}">
    @csrf

    <div style="display:grid;grid-template-columns:1.8fr 1fr 1fr 180px;gap:10px;align-items:center;">
      <select name="idbarang" required style="border:1px solid #f9a8d4;border-radius:10px;padding:8px 10px;">
        <option value="">-- Pilih Barang --</option>
        @foreach($barangList as $b)
          <option value="{{ $b->idbarang }}">{{ $b->nama }}</option>
        @endforeach
      </select>

      <input type="number" name="jumlah_terima" min="1" placeholder="Jumlah"
             style="border:1px solid #f9a8d4;border-radius:10px;padding:8px 10px;">
      <input type="number" name="harga_satuan_terima" min="0" placeholder="Harga per unit"
             style="border:1px solid #f9a8d4;border-radius:10px;padding:8px 10px;">

      <button class="btn" style="background:#ec4899;">Tambah</button>
    </div>
  </form>
</div>

{{-- Daftar detail --}}
<div class="card">
  <h3 style="margin-bottom:10px;color:#be185d;">Barang yang Diterima</h3>
  <table>
    <thead style="background:#fce7f3;">
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
      <tr><td colspan="6" align="center">Belum ada barang diterima.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div style="margin-top:20px;">
  <a href="{{ route('penerimaan.index') }}" class="btn" style="background:#2563eb;">← Kembali</a>
</div>
@endsection

@extends('layouts.master')
@section('title','Update Stok Barang')

@section('content')
<h1 class="page-title">Update Stok Barang</h1>

{{-- ✅ Notifikasi sukses --}}
@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif

{{-- ⚠️ Notifikasi error --}}
@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

{{-- 📦 Form Update Stok --}}
<div class="card">
  <form method="POST" action="{{ route('stok.update.post') }}"
        style="display:grid;
               grid-template-columns:1.8fr 0.8fr 0.8fr 0.8fr 160px;
               gap:10px;
               align-items:center;
               flex-wrap:wrap;">
    @csrf

    {{-- Pilih Barang --}}
    <select name="idbarang" required
            style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
      <option value="">-- Pilih Barang --</option>
      @foreach($barang as $b)
        <option value="{{ $b->idbarang }}">
          {{ $b->nama }} — Rp{{ number_format($b->harga, 0, ',', '.') }}
          (Stok: {{ $b->stok_terakhir }})
        </option>
      @endforeach
    </select>

    {{-- Tipe Stok (Masuk/Keluar) --}}
    <select name="tipe" required
            style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
      <option value="">-- Tipe --</option>
      <option value="M">Masuk</option>
      <option value="K">Keluar</option>
    </select>

    {{-- Jumlah Barang --}}
    <input type="number" name="jumlah" min="1" placeholder="Jumlah"
           style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">

    {{-- Harga Satuan --}}
    <input type="number" name="harga" min="0" step="1" placeholder="Harga per unit"
           style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">

    {{-- Tombol Submit --}}
    <button type="submit" class="btn" style="background:#2563eb">
      Update Stok
    </button>
  </form>
</div>

{{-- 🧾 Daftar Barang & Stok Terkini --}}
<div class="card" style="margin-top:20px;">
  <h3 style="margin-bottom:10px;font-weight:600;">Daftar Barang & Stok Saat Ini</h3>
  <table style="width:100%;border-collapse:collapse;">
    <thead>
      <tr style="background:#f1f5f9;">
        <th style="padding:8px;text-align:left;">ID</th>
        <th style="padding:8px;text-align:left;">Nama Barang</th>
        <th style="padding:8px;text-align:left;">Harga</th>
        <th style="padding:8px;text-align:left;">Stok Terakhir</th>
      </tr>
    </thead>
    <tbody>
      @forelse($barang as $b)
      <tr>
        <td style="padding:8px;">{{ $b->idbarang }}</td>
        <td style="padding:8px;">{{ $b->nama }}</td>
        <td style="padding:8px;">Rp{{ number_format($b->harga, 0, ',', '.') }}</td>
        <td style="padding:8px;">{{ $b->stok_terakhir }}</td>
      </tr>
      @empty
      <tr><td colspan="4" style="text-align:center;padding:10px;">Belum ada data barang.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

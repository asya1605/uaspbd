@extends('layouts.master')
@section('title','Update Stok Produk')

@section('content')
<div style="max-width:900px;margin:0 auto;">
  <h1 class="page-title" style="color:#be185d;text-align:center;margin-bottom:1.5rem;font-weight:700;">
    💖 Update Stok Produk Shashy Beauty 💖
  </h1>

  {{-- ✅ Notifikasi --}}
  @if(session('ok'))
    <div class="alert-ok" style="background:#fce7f3;color:#be185d;border-left:5px solid #f472b6;margin-bottom:15px;">
      {{ session('ok') }}
    </div>
  @endif
  @if($errors->any())
    <div class="alert-err" style="background:#fee2e2;color:#b91c1c;border-left:5px solid #f87171;margin-bottom:15px;">
      {{ $errors->first() }}
    </div>
  @endif

  {{-- 🌷 Form Update --}}
  <div class="card" style="
    background:#fff0f5;
    border:2px solid #fbcfe8;
    border-radius:16px;
    padding:35px 30px;
    margin-bottom:30px;
    box-shadow:0 4px 12px rgba(249,168,212,0.4);
  ">
    <form method="POST" action="{{ route('stok.update.post') }}">
      @csrf

      {{-- Grid Form --}}
      <div style="
        display:flex;
        flex-wrap:wrap;
        justify-content:space-between;
        gap:15px;
      ">
        {{-- Produk --}}
        <select name="idbarang" required
                style="flex:1 1 48%;border:1px solid #f9a8d4;border-radius:10px;padding:10px;background:#fff;">
          <option value="">-- Pilih Produk --</option>
          @foreach($barang as $b)
            <option value="{{ $b->idbarang }}">
              {{ $b->nama }} — Rp{{ number_format($b->harga,0,',','.') }} (Stok: {{ $b->stok_terakhir }})
            </option>
          @endforeach
        </select>

        {{-- Tipe --}}
        <select name="tipe" required
                style="flex:1 1 22%;border:1px solid #f9a8d4;border-radius:10px;padding:10px;background:#fff;">
          <option value="">-- Tipe --</option>
          <option value="M">Masuk 🌸</option>
          <option value="K">Keluar 💅</option>
        </select>

        {{-- Jumlah --}}
        <input type="number" name="jumlah" min="1" placeholder="Jumlah"
               style="flex:1 1 22%;border:1px solid #f9a8d4;border-radius:10px;padding:10px;">

        {{-- Harga --}}
        <input type="number" name="harga" min="0" step="1" placeholder="Harga per unit"
               style="flex:1 1 48%;border:1px solid #f9a8d4;border-radius:10px;padding:10px;">
      </div>

      {{-- Tombol --}}
      <div style="text-align:center;margin-top:25px;">
        <button type="submit"
                style="background:#ec4899;color:white;border:none;border-radius:12px;
                       padding:14px 50px;font-weight:600;font-size:15px;
                       cursor:pointer;box-shadow:0 3px 8px rgba(236,72,153,0.4);
                       transition:0.3s;">
          💗 Simpan Perubahan
        </button>
      </div>
    </form>
  </div>

  {{-- 🧾 Daftar Stok --}}
  <div class="card" style="
    background:#fffafc;
    border:2px solid #fbcfe8;
    border-radius:16px;
    padding:25px;
    box-shadow:0 2px 6px rgba(249,168,212,0.3);
  ">
    <h3 style="margin-bottom:15px;font-weight:700;color:#be185d;display:flex;align-items:center;gap:8px;">
      📋 Daftar Produk & Stok Saat Ini
    </h3>

    <table style="width:100%;border-collapse:collapse;">
      <thead style="background:#fce7f3;">
        <tr>
          <th style="padding:12px;text-align:left;">ID</th>
          <th style="padding:12px;text-align:left;">Nama Produk</th>
          <th style="padding:12px;text-align:left;">Harga</th>
          <th style="padding:12px;text-align:left;">Stok Terakhir</th>
        </tr>
      </thead>
      <tbody>
        @forelse($barang as $b)
        <tr style="border-bottom:1px solid #fbcfe8;">
          <td style="padding:10px;">{{ $b->idbarang }}</td>
          <td style="padding:10px;">{{ $b->nama }}</td>
          <td style="padding:10px;">Rp{{ number_format($b->harga,0,',','.') }}</td>
          <td style="padding:10px;">{{ $b->stok_terakhir }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="4" style="text-align:center;padding:12px;color:#9ca3af;">Belum ada produk 💅</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

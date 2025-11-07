@extends('layouts.master')
@section('title','Detail Retur')

@section('content')
<h1 class="page-title" style="color:#be185d;">🔁 Detail Retur #{{ $retur->idretur }}</h1>

@if(session('ok'))
  <div class="alert-ok" style="background:#fce7f3;color:#be185d;border-left:5px solid #f472b6;">
    {{ session('ok') }}
  </div>
@endif
@if($errors->any())
  <div class="alert-err" style="background:#fee2e2;color:#b91c1c;border-left:5px solid #f87171;">
    {{ $errors->first() }}
  </div>
@endif

{{-- 🌷 Info Retur --}}
<div class="card" style="margin-bottom:20px;">
  <table width="100%" cellpadding="6">
    <tr><td width="180">ID Retur</td><td>: {{ $retur->idretur }}</td></tr>
    <tr><td>ID Penerimaan</td><td>: {{ $retur->idpenerimaan }}</td></tr>
    <tr><td>User Input</td><td>: {{ $retur->username }}</td></tr>
    <tr><td>Tanggal</td><td>: {{ $retur->created_at }}</td></tr>
  </table>
</div>

{{-- 🌸 Form Tambah Barang Retur --}}
<div class="card" style="margin-bottom:20px;">
  <h3 style="margin-bottom:10px;color:#be185d;">Tambah Barang Retur</h3>
  <form method="POST" action="{{ route('retur.addItem', $retur->idretur) }}">
    @csrf
    <div style="display:grid;grid-template-columns:1.8fr 1fr 2fr 180px;gap:10px;align-items:center;">
      <select name="iddetail_penerimaan" required
              style="border:1px solid #f9a8d4;border-radius:10px;padding:8px 10px;">
        <option value="">-- Pilih Barang dari Penerimaan --</option>
        @foreach($detailPenerimaan as $dp)
          <option value="{{ $dp->iddetail_penerimaan }}">
            {{ $dp->nama_barang }} (Diterima: {{ $dp->jumlah_terima }})
          </option>
        @endforeach
      </select>

      <input type="number" name="jumlah" min="1" placeholder="Jumlah retur"
             style="border:1px solid #f9a8d4;border-radius:10px;padding:8px 10px;">

      <input type="text" name="alasan" maxlength="200" placeholder="Alasan retur"
             style="border:1px solid #f9a8d4;border-radius:10px;padding:8px 10px;">

      <button class="btn" style="background:#ec4899;">Tambah</button>
    </div>
  </form>
</div>

{{-- 🌷 Tabel Barang Diretur --}}
<div class="card">
  <h3 style="margin-bottom:10px;color:#be185d;">Barang yang Diretur</h3>
  <table>
    <thead style="background:#fce7f3;">
      <tr>
        <th>ID Detail Retur</th>
        <th>Nama Barang</th>
        <th>Jumlah Retur</th>
        <th>Alasan</th>
      </tr>
    </thead>
    <tbody>
      @forelse($details as $d)
      <tr>
        <td>{{ $d->iddetail_retur }}</td>
        <td>{{ $d->nama_barang }}</td>
        <td>{{ $d->jumlah }}</td>
        <td>{{ $d->alasan }}</td>
      </tr>
      @empty
      <tr><td colspan="4" align="center">Belum ada barang diretur.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div style="margin-top:20px;">
  <a href="{{ route('retur.index') }}" class="btn" style="background:#2563eb;">← Kembali</a>
</div>
@endsection

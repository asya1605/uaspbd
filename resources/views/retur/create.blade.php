@extends('layouts.master')
@section('title','Buat Retur Barang')

@section('content')
<h1 class="page-title" style="color:#be185d;">♻️ Buat Retur Barang</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif
@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

<div class="card">
  <form method="POST" action="{{ route('retur.store') }}">
    @csrf
    <label><b>Pilih Penerimaan</b></label>
    <select name="idpenerimaan" required style="width:100%;padding:8px;border:1px solid #f9a8d4;border-radius:8px;">
      <option value="">-- Pilih Penerimaan --</option>
      @foreach($penerimaanList as $p)
        <option value="{{ $p->idpenerimaan }}" 
          {{ $selectedPenerimaan && $selectedPenerimaan->idpenerimaan == $p->idpenerimaan ? 'selected' : '' }}>
          {{ $p->idpenerimaan }}
        </option>
      @endforeach
    </select>

    <label style="margin-top:10px;"><b>User Input</b></label>
    <select name="iduser" required style="width:100%;padding:8px;border:1px solid #f9a8d4;border-radius:8px;">
      @foreach($users as $u)
        <option value="{{ $u->iduser }}">{{ $u->username }}</option>
      @endforeach
    </select>

    <button type="submit" class="btn" style="background:#ec4899;margin-top:12px;">Simpan Retur</button>
  </form>
</div>

@if($selectedPenerimaan)
  <div class="card" style="margin-top:20px;">
    <h3>Barang dari Penerimaan #{{ $selectedPenerimaan->idpenerimaan }}</h3>
    <table>
      <thead style="background:#fce7f3;">
        <tr>
          <th>Nama Barang</th>
          <th>Jumlah Diterima</th>
          <th>Harga</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($details as $d)
        <tr>
          <td>{{ $d->nama_barang }}</td>
          <td>{{ $d->jumlah_terima }}</td>
          <td>Rp{{ number_format($d->harga_satuan_terima,0,',','.') }}</td>
          <td>
            <a href="{{ route('retur.items', ['idretur' => $selectedPenerimaan->idpenerimaan]) }}" 
               class="btn" style="background:#f59e0b;">Return</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endif
@endsection

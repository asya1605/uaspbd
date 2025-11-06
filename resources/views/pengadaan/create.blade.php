@extends('layouts.master')
@section('title','Tambah Pengadaan')

@section('content')
<h1 class="page-title">Tambah Pengadaan Baru</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif
@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

<div class="card">
  <form method="POST" action="{{ route('pengadaan.store') }}">
    @csrf

    <div style="margin-bottom:12px">
      <label>User Input</label><br>
      <select name="user_iduser" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px">
        <option value="">-- Pilih User --</option>
        @foreach($users as $u)
          <option value="{{ $u->iduser }}">{{ $u->username }}</option>
        @endforeach
      </select>
    </div>

    <div style="margin-bottom:12px">
      <label>Vendor</label><br>
      <select name="vendor_idvendor" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px">
        <option value="">-- Pilih Vendor --</option>
        @foreach($vendors as $v)
          <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
        @endforeach
      </select>
    </div>

    <div style="margin-bottom:12px">
      <label>Status</label><br>
      <select name="status" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px">
        <option value="1">Aktif</option>
        <option value="0">Nonaktif</option>
      </select>
    </div>

    <div style="margin-bottom:12px">
      <label>Subtotal Nilai</label><br>
      <input type="number" name="subtotal_nilai" value="0" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px">
    </div>

    <div style="margin-bottom:12px">
      <label>PPN (11%)</label><br>
      <input type="number" name="ppn" value="0" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px">
    </div>

    <button class="btn" style="background:#2563eb">Simpan Pengadaan</button>
    <a href="{{ route('pengadaan.index') }}" style="margin-left:10px;text-decoration:none;color:#111">Batal</a>
  </form>
</div>
@endsection

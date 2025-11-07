@extends('layouts.master')
@section('title','Tambah Penerimaan')

@section('content')
<h1 class="page-title" style="color:#be185d;">💗 Tambah Penerimaan Baru</h1>

@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif
@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif

<div class="card" style="background:#fff0f5;border:2px solid #fbcfe8;border-radius:16px;padding:30px;">
  <form method="POST" action="{{ route('penerimaan.store') }}">
    @csrf

    <div style="margin-bottom:12px;">
      <label for="idpengadaan" style="font-weight:600;">ID Pengadaan</label>
      <select name="idpengadaan" id="idpengadaan" required
              style="width:100%;padding:10px;border:1px solid #f9a8d4;border-radius:8px;">
        <option value="">-- Pilih Pengadaan --</option>
        @foreach($pengadaan as $p)
          <option value="{{ $p->idpengadaan }}">{{ $p->idpengadaan }}</option>
        @endforeach
      </select>
    </div>

    <div style="margin-bottom:12px;">
      <label for="iduser" style="font-weight:600;">User Input</label>
      <select name="iduser" id="iduser" required
              style="width:100%;padding:10px;border:1px solid #f9a8d4;border-radius:8px;">
        <option value="">-- Pilih User --</option>
        @foreach($users as $u)
          <option value="{{ $u->iduser }}">{{ $u->username }}</option>
        @endforeach
      </select>
    </div>

    <div style="margin-bottom:12px;">
      <label for="status" style="font-weight:600;">Status</label>
      <select name="status" id="status" required
              style="width:100%;padding:10px;border:1px solid #f9a8d4;border-radius:8px;">
        <option value="1">Aktif</option>
        <option value="0">Nonaktif</option>
      </select>
    </div>

    <button type="submit"
            style="background:#ec4899;color:white;border:none;border-radius:10px;
                   padding:12px 40px;font-weight:600;cursor:pointer;">
      💗 Simpan Penerimaan
    </button>
    <a href="{{ route('penerimaan.index') }}" style="margin-left:10px;color:#555;">Batal</a>
  </form>
</div>
@endsection

@extends('layouts.master')
@section('title','Tambah Pengadaan')

@section('content')
<style>
  /* 🌸 General style */
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
    padding: 30px 24px;
    margin-bottom: 28px;
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

  .btn-save {
    background: linear-gradient(90deg, #ff99b8, #ffaec9);
  }

  .btn-cancel {
    color: #a32f4a;
    font-weight: 600;
    text-decoration: none;
    margin-left: 12px;
    transition: 0.2s;
  }
  .btn-cancel:hover { text-decoration: underline; color: #d94f6a; }

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

<h1 class="page-title">Tambah Pengadaan Baru 💼</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif
@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

<div class="card">
  <form method="POST" action="{{ route('pengadaan.store') }}">
    @csrf

    <div style="margin-bottom:16px">
      <label>User Input</label>
      <select name="user_iduser" required>
        <option value="">-- Pilih User --</option>
        @foreach($users as $u)
          <option value="{{ $u->iduser }}">{{ $u->username }}</option>
        @endforeach
      </select>
    </div>

    <div style="margin-bottom:16px">
      <label>Vendor</label>
      <select name="vendor_idvendor" required>
        <option value="">-- Pilih Vendor --</option>
        @foreach($vendors as $v)
          <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
        @endforeach
      </select>
    </div>

    <div style="margin-bottom:16px">
      <label>Status</label>
      <select name="status" required>
        <option value="1">Aktif</option>
        <option value="0">Nonaktif</option>
      </select>
    </div>

    <div style="margin-bottom:16px">
      <label>Subtotal Nilai</label>
      <input type="number" name="subtotal_nilai" value="0" required placeholder="Masukkan subtotal (Rp)">
    </div>

    <div style="margin-bottom:20px">
      <label>PPN (11%)</label>
      <input type="number" name="ppn" value="0" required placeholder="Masukkan nilai PPN">
    </div>

    <button class="btn btn-save" type="submit">Simpan Pengadaan</button>
    <a href="{{ route('pengadaan.index') }}" class="btn-cancel">Batal</a>
  </form>
</div>
@endsection

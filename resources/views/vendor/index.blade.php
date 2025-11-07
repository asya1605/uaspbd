@extends('layouts.master')
@section('title','Kelola Vendor')

@section('content')
<style>
  /* 🌸 Table style */
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

  tbody tr:nth-child(even) {
    background: #fff4f7;
  }

  tbody tr:hover {
    background: #ffe8ef;
    transition: 0.2s;
  }

  tbody td {
    padding: 10px 12px;
    vertical-align: middle;
    color: #4b2e31;
  }

  /* 🌸 Input dan Select */
  input[type="text"],
  select {
    border: 1px solid #fbc4d8;
    border-radius: 8px;
    padding: 8px 10px;
    font-size: 14px;
    background: #fff9fa;
    transition: 0.2s ease;
  }

  input:focus,
  select:focus {
    outline: none;
    border-color: #ff8fb2;
    box-shadow: 0 0 5px #ffc1d4;
  }

  /* 🌸 Tombol */
  .btn {
    border: none;
    border-radius: 8px;
    padding: 8px 14px;
    font-weight: 600;
    color: white;
    cursor: pointer;
    transition: 0.2s ease;
  }

  .btn:hover {
    transform: scale(1.05);
  }

  .btn-add {
    background: linear-gradient(90deg, #ff99b8, #ffaec9);
  }

  .btn-update {
    background: linear-gradient(90deg, #6ee7b7, #34d399);
  }

  .btn-delete {
    background: linear-gradient(90deg, #ff7b9e, #ff5178);
  }

  /* 🌸 Card */
  .card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 6px 18px rgba(198,124,143,0.15);
    border: 1px solid #ffd6e3;
    padding: 24px;
    margin-bottom: 28px;
  }

  .page-title {
    color: #c67c8f;
    text-align: center;
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
  }

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

<h1 class="page-title">Kelola Vendor 🏷️</h1>

@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif
@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif

{{-- 🌷 Form tambah vendor --}}
<div class="card">
  <form method="POST" action="{{ route('vendor.store') }}"
        style="display:grid;grid-template-columns:2fr 140px 140px 160px;gap:10px;align-items:center;">
    @csrf
    <input name="nama_vendor" placeholder="Nama vendor" required>
    <select name="badan_hukum" required>
      <option value="Y">Badan Usaha (Y)</option>
      <option value="N">Perorangan (N)</option>
    </select>
    <select name="status" required>
      <option value="1">Aktif</option>
      <option value="0">Nonaktif</option>
    </select>
    <button class="btn btn-add">+ Tambah Vendor</button>
  </form>
</div>

{{-- 🌷 Tabel Vendor --}}
<div class="card">
  <table>
    <thead>
      <tr>
        <th width="70">ID</th>
        <th>Nama Vendor</th>
        <th width="150">Badan Hukum</th>
        <th width="110">Status</th>
        <th width="240">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $v)
      <tr>
        <td>{{ $v->idvendor }}</td>
        <td>
          <form method="POST" action="{{ route('vendor.update',$v->idvendor) }}" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            @csrf
            <input type="text" name="nama_vendor" value="{{ $v->nama_vendor }}">
        </td>
        <td>
          <select name="badan_hukum">
            <option value="Y" {{ $v->badan_hukum=='Y'?'selected':'' }}>Badan Usaha</option>
            <option value="N" {{ $v->badan_hukum=='N'?'selected':'' }}>Perorangan</option>
          </select>
        </td>
        <td>
          <select name="status">
            <option value="1" {{ $v->status==1?'selected':'' }}>Aktif</option>
            <option value="0" {{ $v->status==0?'selected':'' }}>Nonaktif</option>
          </select>
        </td>
        <td>
          <button class="btn btn-update" style="margin-right:6px;">Update</button>
          </form>

          <form method="POST" action="{{ route('vendor.delete',$v->idvendor) }}"
                onsubmit="return confirm('Hapus vendor ini?')" style="display:inline">
            @csrf
            <button class="btn btn-delete">Hapus</button>
          </form>
        </td>
      </tr>
      @empty
        <tr><td colspan="5" align="center" style="padding:15px;">Belum ada vendor 😢</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

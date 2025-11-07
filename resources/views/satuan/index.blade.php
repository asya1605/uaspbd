@extends('layouts.master')
@section('title','Kelola Satuan')

@section('content')
<style>
  /* 🌸 Tabel */
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

  /* 🌸 Input & Select */
  input[type="text"], select {
    border: 1px solid #fbc4d8;
    border-radius: 8px;
    padding: 8px 10px;
    font-size: 14px;
    background: #fff9fa;
    transition: 0.2s ease;
  }

  input:focus, select:focus {
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
  .btn:hover { transform: scale(1.05); }

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

<h1 class="page-title">Kelola Satuan ⚖️</h1>

@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif

{{-- 🌷 Form tambah satuan --}}
<div class="card">
  <form method="POST" action="{{ route('satuan.store') }}" 
        style="display:flex;gap:10px;align-items:center;">
    @csrf
    <input name="nama_satuan" placeholder="Nama satuan (mis. pcs / kg)" required>
    <select name="status">
      <option value="1">Aktif</option>
      <option value="0">Nonaktif</option>
    </select>
    <button class="btn btn-add">+ Tambah Satuan</button>
  </form>
</div>

{{-- 🌷 Tabel satuan --}}
<div class="card">
  <table>
    <thead>
      <tr>
        <th width="80">ID</th>
        <th>Nama Satuan</th>
        <th>Status</th>
        <th width="240">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $r)
      <tr>
        <td>{{ $r->idsatuan }}</td>
        <td>{{ $r->nama_satuan }}</td>
        <td>{{ $r->status_text ?? ($r->status == 1 ? 'Aktif' : 'Nonaktif') }}</td>
        <td>
          <form method="POST" action="{{ route('satuan.update', $r->idsatuan) }}" 
                style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
            @csrf
            <input type="text" name="nama_satuan" value="{{ $r->nama_satuan }}">
            <select name="status">
              <option value="1" @selected($r->status == 1)>Aktif</option>
              <option value="0" @selected($r->status == 0)>Nonaktif</option>
            </select>
            <button class="btn btn-update">Update</button>
          </form>

          <form method="POST" action="{{ route('satuan.delete', $r->idsatuan) }}" 
                onsubmit="return confirm('Hapus satuan ini?')" style="display:inline">
            @csrf
            <button class="btn btn-delete" style="margin-top:6px;">Hapus</button>
          </form>
        </td>
      </tr>
      @empty
        <tr><td colspan="4" align="center" style="padding:15px;">Belum ada satuan 😢</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

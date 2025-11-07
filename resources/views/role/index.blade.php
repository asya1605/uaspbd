@extends('layouts.master')
@section('title','Kelola Role')

@section('content')
<style>
  /* 🌸 Style khusus halaman ini */
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

  input[type="text"] {
    border: 1px solid #fbc4d8;
    border-radius: 8px;
    padding: 8px 10px;
    font-size: 14px;
    background: #fff9fa;
  }

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
    padding: 24px;
    margin-bottom: 28px;
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

<h1 class="page-title">Kelola Role 💼</h1>

@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif

{{-- 🌸 Form tambah Role --}}
<div class="card">
  <form method="POST" action="{{ route('role.store') }}" style="display:flex;gap:10px;align-items:center;">
    @csrf
    <input name="nama_role" placeholder="Nama role (mis. super_admin / admin)" required>
    <button class="btn btn-add">+ Tambah Role</button>
  </form>
</div>

{{-- 🌸 Tabel Role --}}
<div class="card">
  <table>
    <thead>
      <tr>
        <th width="80">ID</th>
        <th>Nama Role</th>
        <th width="220">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $r)
      <tr>
        <td>{{ $r->idrole }}</td>
        <td>
          <form method="POST" action="{{ route('role.update',$r->idrole) }}" style="display:flex;gap:10px;align-items:center;">
            @csrf
            <input type="text" name="nama_role" value="{{ $r->nama_role }}">
            <button class="btn btn-update">Update</button>
          </form>
        </td>
        <td>
          <form method="POST" action="{{ route('role.delete',$r->idrole) }}" 
                onsubmit="return confirm('Hapus role ini?')" style="display:inline">
            @csrf
            <button class="btn btn-delete">Hapus</button>
          </form>
        </td>
      </tr>
      @empty
        <tr><td colspan="3" style="text-align:center;padding:15px;">Belum ada role 😢</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

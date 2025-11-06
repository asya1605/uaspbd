@extends('layouts.master')
@section('title','Kelola Role')

@section('content')
<h1 class="page-title">Kelola Role</h1>

@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif

{{-- Form tambah Role --}}
<div class="card">
  <form method="POST" action="{{ route('role.store') }}" style="display:flex;gap:10px;align-items:center">
    @csrf
    <input name="nama_role" placeholder="Nama role (mis. super_admin / admin)" required
           style="flex:1;border:1px solid #e5e7eb;border-radius:10px;padding:10px">
    <button class="btn">Tambah Role</button>
  </form>
</div>

{{-- Tabel Role --}}
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
          <form method="POST" action="{{ route('role.update',$r->idrole) }}" style="display:flex;gap:10px;align-items:center">
            @csrf
            <input type="text" name="nama_role" value="{{ $r->nama_role }}"
                   style="flex:1;border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px">
            <button class="btn" style="background:#10b981">Update</button>
          </form>
        </td>
        <td>
          <form method="POST" action="{{ route('role.delete',$r->idrole) }}"
                onsubmit="return confirm('Hapus role ini?')" style="display:inline">
            @csrf
            <button class="btn" style="background:#ef4444">Hapus</button>
          </form>
        </td>
      </tr>
      @empty
        <tr><td colspan="3">Belum ada role.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

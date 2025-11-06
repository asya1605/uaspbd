@extends('layouts.master')
@section('title','Kelola User')

@section('content')
<h1 class="page-title">Kelola User</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif
@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

{{-- Form tambah user --}}
<div class="card" style="margin-bottom:14px">
  <form method="POST" action="{{ route('users.store') }}"
        style="display:grid;grid-template-columns:1.5fr 1.5fr 1.5fr 1fr 1fr 160px;gap:8px;align-items:center">
    @csrf
    <input name="username" placeholder="Username" required
           style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
    <input name="email" type="email" placeholder="Email"
           style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
    <input name="password" type="password" placeholder="Password" required
           style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
    <select name="idrole" required style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
      <option value="">Role</option>
      @foreach($roles as $r)
        <option value="{{ $r->idrole }}">{{ $r->nama_role }}</option>
      @endforeach
    </select>
    <select name="status" required style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
      <option value="1">Aktif</option>
      <option value="0">Nonaktif</option>
    </select>
    <button class="btn">Tambah User</button>
  </form>
</div>

{{-- Tabel user --}}
<div class="card">
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $u)
      <tr>
        <td>{{ $u->iduser }}</td>
        <td>
          <form method="POST" action="{{ route('users.update', $u->iduser) }}" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
            @csrf
            <input type="text" name="username" value="{{ $u->username }}"
                   style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px;min-width:150px">
        </td>
        <td>
          <input type="email" name="email" value="{{ $u->email }}"
                 style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px;min-width:180px">
        </td>
        <td>
          <select name="idrole" style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px">
            @foreach($roles as $r)
              <option value="{{ $r->idrole }}" {{ $r->idrole == $u->idrole ? 'selected' : '' }}>
                {{ $r->nama_role }}
              </option>
            @endforeach
          </select>
        </td>
        <td>
          <select name="status" style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px">
            <option value="1" {{ $u->status == 1 ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ $u->status == 0 ? 'selected' : '' }}>Nonaktif</option>
          </select>
        </td>
        <td>
          <button class="btn" style="background:#10b981;margin-right:6px">Update</button>
          </form>

          <form method="POST" action="{{ route('users.delete', $u->iduser) }}"
                onsubmit="return confirm('Hapus user ini?')" style="display:inline">
            @csrf
            <button class="btn" style="background:#ef4444">Hapus</button>
          </form>
        </td>
      </tr>
      @empty
        <tr><td colspan="6" align="center">Belum ada user.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

@extends('layout')
@section('title','Kelola User')

@section('content')
<h1 class="page-title">Kelola User</h1>

@if($errors->any())
  <div class="card" style="border:1px solid #fecaca;background:#fff1f2;color:#b91c1c;margin-bottom:10px">
    {{ $errors->first() }}
  </div>
@endif

@if(session('ok'))
  <div class="card" style="border:1px solid #b6f0c2;background:#e6ffed;color:#166534;margin-bottom:10px">
    {{ session('ok') }}
  </div>
@endif

{{-- Form tambah user --}}
<div class="card" style="margin-bottom:14px">
  <form method="post" action="/users" style="display:grid;grid-template-columns:repeat(6,1fr);gap:8px;align-items:center">
    @csrf
    <input name="username" placeholder="Username" required
           style="border:1px solid #e5e7eb;border-radius:10px;padding:10px;grid-column:span 2">
    <input name="email" type="email" placeholder="Email (opsional)"
           style="border:1px solid #e5e7eb;border-radius:10px;padding:10px;grid-column:span 2">
    <input name="password" type="password" placeholder="Password" required
           style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
    <select name="status" style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
      <option value="1">Aktif</option>
      <option value="0">Nonaktif</option>
    </select>

    <select name="idrole" required
            style="border:1px solid #e5e7eb;border-radius:10px;padding:10px;grid-column:span 2">
      @foreach($roles as $r)
        <option value="{{ $r->idrole }}">{{ $r->nama_role }}</option>
      @endforeach
    </select>

    <div style="grid-column:span 4"></div>
    <button class="btn" style="grid-column:span 2">Tambah User</button>
  </form>
</div>

{{-- Tabel users + inline edit --}}
<div class="card">
  <table border="0" width="100%" cellpadding="10" style="border-collapse:collapse">
    <thead>
      <tr style="background:#f8fafc">
        <th align="left" width="70">ID</th>
        <th align="left">Username</th>
        <th align="left">Email</th>
        <th align="left" width="130">Role</th>
        <th align="left" width="110">Status</th>
        <th align="left" width="260">Aksi</th>
      </tr>
    </thead>
    <tbody>
    @forelse($rows as $u)
      <tr style="border-top:1px solid #eef2f7">
        <td>{{ $u->iduser }}</td>
        <td>
          <form method="post" action="/users/{{ $u->iduser }}/update" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
            @csrf
            <input type="text" name="username" value="{{ $u->username }}"
                   style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px;min-width:180px">
            <input type="email" name="email" value="{{ $u->email }}"
                   style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px;min-width:220px">
        </td>
        <td>
            <select name="idrole" style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px">
              @foreach($roles as $r)
                <option value="{{ $r->idrole }}" {{ $u->idrole==$r->idrole?'selected':'' }}>
                  {{ $r->nama_role }}
                </option>
              @endforeach
            </select>
        </td>
        <td>
          <select name="status" ...>
            <option value="1">Aktif</option>
            <option value="0">Nonaktif</option>
          </select>
        </td>
        <td>
            <input type="password" name="password" placeholder="(opsional) ganti password"
                   style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px;min-width:200px">
            <button class="btn" style="background:#10b981;margin-left:6px">Update</button>
          </form>

          <form method="post" action="/users/{{ $u->iduser }}/delete"
                onsubmit="return confirm('Hapus user ini?')" style="display:inline">
            @csrf
            <button class="btn" style="background:#ef4444;margin-top:6px">Hapus</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="6">Belum ada user.</td></tr>
    @endforelse
    </tbody>
  </table>
</div>
@endsection

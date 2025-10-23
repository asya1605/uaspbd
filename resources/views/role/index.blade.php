@extends('layout')
@section('title','Kelola Role')

@section('content')
<h1 class="page-title">Kelola Role</h1>

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

{{-- Form tambah Role --}}
<div class="card" style="margin-bottom:14px">
  <form method="post" action="/role" style="display:flex;gap:8px;align-items:center">
    @csrf
    <input name="nama_role" placeholder="Nama role (mis. super_admin / admin)" required
           style="flex:1;border:1px solid #e5e7eb;border-radius:10px;padding:10px">
    <button class="btn">Tambah Role</button>
  </form>
</div>

{{-- Tabel Role --}}
<div class="card">
  <table border="0" width="100%" cellpadding="10" style="border-collapse:collapse">
    <thead>
      <tr style="background:#f8fafc">
        <th align="left" width="80">ID</th>
        <th align="left">Nama Role</th>
        <th align="left" width="250">Aksi</th>
      </tr>
    </thead>
    <tbody>
    @forelse($rows as $r)
      <tr style="border-top:1px solid #eef2f7">
        <td>{{ $r->idrole }}</td>
        <td>
          {{-- inline edit --}}
          <form method="post" action="/role/{{ $r->idrole }}/update" style="display:flex;gap:8px;align-items:center">
            @csrf
            <input type="text" name="nama_role" value="{{ $r->nama_role }}"
                   style="flex:1;border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px">
            <button class="btn" style="background:#10b981">Update</button>
          </form>
        </td>
        <td>
          <form method="post" action="/role/{{ $r->idrole }}/delete"
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

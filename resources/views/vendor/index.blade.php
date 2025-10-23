@extends('layout')
@section('title','Kelola Vendor')

@section('content')
<h1 class="page-title">Kelola Vendor</h1>

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

{{-- Form tambah vendor --}}
<div class="card" style="margin-bottom:14px">
  <form method="post" action="/vendor"
        style="display:grid;grid-template-columns:2fr 120px 120px 140px;gap:8px;align-items:center">
    @csrf
    <input name="nama_vendor" placeholder="Nama vendor" required
           style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
    <select name="badan_hukum" required
            style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
      <option value="Y">Badan Hukum (Y)</option>
      <option value="N">Tidak (N)</option>
    </select>
    <select name="status" required
            style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
      <option value="1">Aktif</option>
      <option value="0">Nonaktif</option>
    </select>
    <button class="btn">Tambah Vendor</button>
  </form>
</div>

{{-- Tabel vendor + inline edit --}}
<div class="card">
  <table border="0" width="100%" cellpadding="10" style="border-collapse:collapse">
    <thead>
      <tr style="background:#f8fafc">
        <th align="left" width="70">ID</th>
        <th align="left">Nama Vendor</th>
        <th align="left" width="140">Badan Hukum</th>
        <th align="left" width="110">Status</th>
        <th align="left" width="250">Aksi</th>
      </tr>
    </thead>
    <tbody>
    @forelse($rows as $v)
      <tr style="border-top:1px solid #eef2f7">
        <td>{{ $v->idvendor }}</td>
        <td>
          <form method="post" action="/vendor/{{ $v->idvendor }}/update"
                style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
            @csrf
            <input type="text" name="nama_vendor" value="{{ $v->nama_vendor }}"
                   style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px;min-width:260px">
        </td>
        <td>
            <select name="badan_hukum"
                    style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px">
              <option value="Y" {{ $v->badan_hukum==='B'?'selected':'' }}>Badan Usaha</option>
              <option value="N" {{ $v->badan_hukum==='P'?'selected':'' }}>Perorangan</option>
            </select>
        </td>
        <td>
            <select name="status"
                    style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px">
              <option value="1" {{ (string)$v->status==='1'?'selected':'' }}>Aktif</option>
              <option value="0" {{ (string)$v->status==='0'?'selected':'' }}>Nonaktif</option>
            </select>
        </td>
        <td>
            <button class="btn" style="background:#10b981;margin-right:6px">Update</button>
          </form>

          <form method="post" action="/vendor/{{ $v->idvendor }}/delete"
                onsubmit="return confirm('Hapus vendor ini?')" style="display:inline">
            @csrf
            <button class="btn" style="background:#ef4444">Hapus</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="5">Belum ada vendor.</td></tr>
    @endforelse
    </tbody>
  </table>
</div>
@endsection

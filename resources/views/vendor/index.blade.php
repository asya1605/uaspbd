@extends('layouts.master')
@section('title','Kelola Vendor')

@section('content')
<h1 class="page-title">Kelola Vendor</h1>

@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif
@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif

{{-- Form tambah vendor --}}
<div class="card">
  <form method="POST" action="{{ route('vendor.store') }}"
        style="display:grid;grid-template-columns:2fr 140px 120px 140px;gap:10px;align-items:center">
    @csrf
    <input name="nama_vendor" placeholder="Nama vendor" required
           style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
    <select name="badan_hukum" required style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
      <option value="Y">Badan Usaha (Y)</option>
      <option value="N">Perorangan (N)</option>
    </select>
    <select name="status" required style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
      <option value="1">Aktif</option>
      <option value="0">Nonaktif</option>
    </select>
    <button class="btn">Tambah Vendor</button>
  </form>
</div>

{{-- Tabel vendor --}}
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
          <form method="POST" action="{{ route('vendor.update',$v->idvendor) }}" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            @csrf
            <input type="text" name="nama_vendor" value="{{ $v->nama_vendor }}"
                   style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px;min-width:200px;">
        </td>
        <td>
          <select name="badan_hukum" style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px">
            <option value="Y" {{ $v->badan_hukum=='Y'?'selected':'' }}>Badan Usaha</option>
            <option value="N" {{ $v->badan_hukum=='N'?'selected':'' }}>Perorangan</option>
          </select>
        </td>
        <td>
          <select name="status" style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px">
            <option value="1" {{ $v->status==1?'selected':'' }}>Aktif</option>
            <option value="0" {{ $v->status==0?'selected':'' }}>Nonaktif</option>
          </select>
        </td>
        <td>
          <button class="btn" style="background:#10b981;margin-right:6px">Update</button>
          </form>

          <form method="POST" action="{{ route('vendor.delete',$v->idvendor) }}"
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

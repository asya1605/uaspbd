@extends('layouts.master')
@section('title','Kelola Retur Barang')

@section('content')
<h1 class="page-title" style="color:#be185d;">🔁 Daftar Retur Barang</h1>

@if(session('ok'))
  <div class="alert-ok" style="background:#fce7f3;color:#be185d;border-left:5px solid #f472b6;">
    {{ session('ok') }}
  </div>
@endif
@if($errors->any())
  <div class="alert-err" style="background:#fee2e2;color:#b91c1c;border-left:5px solid #f87171;">
    {{ $errors->first() }}
  </div>
@endif

{{-- 🌷 Header --}}
<div class="card" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
  <h2 style="margin:0;color:#c67c8f;font-weight:700;">Data Retur</h2>
  <a href="{{ route('retur.create') }}" class="btn" style="background:#ec4899;">+ Tambah Retur</a>
</div>

{{-- 🌸 Tabel Retur --}}
<div class="card">
  <table>
    <thead style="background:#fce7f3;">
      <tr>
        <th>ID Retur</th>
        <th>ID Penerimaan</th>
        <th>User</th>
        <th>Tanggal</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $r)
      <tr>
        <td>{{ $r->idretur }}</td>
        <td>{{ $r->idpenerimaan }}</td>
        <td>{{ $r->username }}</td>
        <td>{{ $r->created_at }}</td>
        <td>
          <a href="{{ route('retur.items', $r->idretur) }}" class="btn" style="background:#10b981;">Detail</a>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5" align="center" style="padding:12px;">Belum ada data retur 😢</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

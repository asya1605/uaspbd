@extends('layouts.master')
@section('title','Kelola Satuan')

@section('content')
<h1 class="page-title">Kelola Satuan</h1>

@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif

{{-- Form tambah Satuan --}}
<div class="card">
  <form method="POST" action="{{ route('satuan.store') }}" style="display:flex;gap:10px;align-items:center">
    @csrf
    <input name="nama_satuan" placeholder="Nama satuan (mis. pcs / kg)" required
           style="flex:1;border:1px solid #e5e7eb;border-radius:10px;padding:10px">
    <select name="status" style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px">
      <option value="1">Aktif</option>
      <option value="0">Nonaktif</option>
    </select>
    <button class="btn">Tambah Satuan</button>
  </form>
</div>

{{-- Tabel Satuan --}}
<div class="card">
  <table>
    <thead>
      <tr>
        <th width="80">ID</th>
        <th>Nama Satuan</th>
        <th>Status</th>
        <th width="220">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $r)
      <tr>
        <td>{{ $r->idsatuan }}</td>
        <td>{{ $r->nama_satuan }}</td>
        <td>{{ $r->status_text ?? ($r->status == 1 ? 'Aktif' : 'Nonaktif') }}</td>
        <td>
          <form method="POST" action="{{ route('satuan.update', $r->idsatuan) }}" style="display:flex;gap:8px;align-items:center">
            @csrf
            <input type="text" name="nama_satuan" value="{{ $r->nama_satuan }}"
                   style="flex:1;border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px">
            <select name="status" style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px">
              <option value="1" @selected($r->status == 1)>Aktif</option>
              <option value="0" @selected($r->status == 0)>Nonaktif</option>
            </select>
            <button class="btn" style="background:#10b981">Update</button>
          </form>
          <form method="POST" action="{{ route('satuan.delete', $r->idsatuan) }}" 
                onsubmit="return confirm('Hapus satuan ini?')" style="display:inline">
            @csrf
            <button class="btn" style="background:#ef4444">Hapus</button>
          </form>
        </td>
      </tr>
      @empty
        <tr><td colspan="4">Belum ada satuan.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

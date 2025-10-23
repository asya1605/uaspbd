@extends('layout')
@section('title','Satuan')

@section('content')
<h1 class="page-title">Kelola Satuan</h1>

{{-- Form tambah satuan --}}
<div class="card" style="margin-bottom:14px">
  <form method="post" action="/satuan" style="display:flex;gap:8px;align-items:center">
    @csrf
    <input name="nama_satuan" placeholder="Nama satuan" required
           style="flex:1;border:1px solid #e5e7eb;border-radius:10px;padding:10px">
    <select name="status"
            style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
      <option value="1">Aktif</option>
      <option value="0">Nonaktif</option>
    </select>
    <button class="btn">Tambah</button>
  </form>
</div>

{{-- Tabel data --}}
<div class="card">
  <table border="0" width="100%" cellpadding="10" style="border-collapse:collapse">
    <thead>
      <tr style="background:#f8fafc">
        <th align="left" width="80">ID</th>
        <th align="left">Nama Satuan</th>
        <th align="left" width="120">Status</th>
        <th align="left" width="180">Aksi</th>
      </tr>
    </thead>
    <tbody>
    @forelse($rows as $r)
      <tr style="border-top:1px solid #eef2f7">
        <td>{{ $r->idsatuan }}</td>
        <td>{{ $r->nama_satuan }}</td>
        <td>{{ $r->status ? 'Aktif' : 'Nonaktif' }}</td>
        <td>
          {{-- contoh tombol hapus --}}
          <form method="post" action="/satuan/{{ $r->idsatuan }}/delete"
                onsubmit="return confirm('Hapus satuan ini?')" style="display:inline">
            @csrf
            <button class="btn" style="background:#ef4444">Hapus</button>
          </form>

          {{-- contoh tombol edit (opsional sederhana, kirim nama baru) --}}
          <form method="post" action="/satuan/{{ $r->idsatuan }}/update" style="display:inline;margin-left:6px">
            @csrf
            <input type="hidden" name="status" value="{{ $r->status }}">
            <input type="text" name="nama_satuan" value="{{ $r->nama_satuan }}"
                   style="border:1px solid #e5e7eb;border-radius:8px;padding:6px 8px;width:180px">
            <button class="btn" style="background:#10b981;margin-left:4px">Update</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="4">Belum ada data.</td></tr>
    @endforelse
    </tbody>
  </table>
</div>
@endsection

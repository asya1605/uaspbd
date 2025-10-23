@extends('layout')
@section('title','Kelola Barang')

@section('content')
<h1 class="page-title">Kelola Barang</h1>

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

{{-- Form tambah --}}
<div class="card" style="margin-bottom:14px">
  <form method="post" action="/barang"
        style="display:grid;grid-template-columns:110px 1.5fr 1fr 1fr 110px 120px;gap:8px;align-items:center">
    @csrf
    <select name="jenis" required style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
      <option value="">Jenis</option>
      <option value="A">A</option><option value="B">B</option><option value="C">C</option>
    </select>
    <input name="nama" placeholder="Nama barang" required
           style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
    <select name="idsatuan" required style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
      <option value="">Satuan</option>
      @foreach($satuan as $s)
        <option value="{{ $s->idsatuan }}">{{ $s->nama_satuan }}</option>
      @endforeach
    </select>
    <input name="harga" type="number" min="0" step="1" placeholder="Harga"
           style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
    <select name="status" required style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
      <option value="1">Aktif</option><option value="0">Nonaktif</option>
    </select>
    <button class="btn">Tambah Barang</button>
  </form>
</div>

{{-- Tabel + inline edit --}}
<div class="card">
  <table border="0" width="100%" cellpadding="10" style="border-collapse:collapse">
    <thead>
      <tr style="background:#f8fafc">
        <th align="left" width="70">ID</th>
        <th align="left" width="80">Jenis</th>
        <th align="left">Nama</th>
        <th align="left" width="160">Satuan</th>
        <th align="left" width="140">Harga</th>
        <th align="left" width="110">Status</th>
        <th align="left" width="260">Aksi</th>
      </tr>
    </thead>
    <tbody>
    @forelse($rows as $b)
      <tr style="border-top:1px solid #eef2f7">
        <td>{{ $b->idbarang }}</td>
        <td>
          <form method="post" action="/barang/{{ $b->idbarang }}/update"
                style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
            @csrf
            <select name="jenis" style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px">
              @foreach(['A','B','C'] as $j)
                <option value="{{ $j }}" {{ $b->jenis===$j?'selected':'' }}>{{ $j }}</option>
              @endforeach
            </select>
        </td>
        <td>
            <input type="text" name="nama" value="{{ $b->nama }}"
                   style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px;min-width:220px">
        </td>
        <td>
            <select name="idsatuan" style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px">
              @foreach($satuan as $s)
                <option value="{{ $s->idsatuan }}" {{ $b->idsatuan==$s->idsatuan?'selected':'' }}>
                  {{ $s->nama_satuan }}
                </option>
              @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="harga" min="0" step="1" value="{{ $b->harga }}"
                   style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px;min-width:140px">
        </td>
        <td>
            <select name="status" style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px">
              <option value="1" {{ (string)$b->status==='1'?'selected':'' }}>Aktif</option>
              <option value="0" {{ (string)$b->status==='0'?'selected':'' }}>Nonaktif</option>
            </select>
        </td>
        <td>
            <button class="btn" style="background:#10b981;margin-right:6px">Update</button>
          </form>

          <form method="post" action="/barang/{{ $b->idbarang }}/delete"
                onsubmit="return confirm('Hapus barang ini?')" style="display:inline">
            @csrf
            <button class="btn" style="background:#ef4444">Hapus</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="7">Belum ada barang.</td></tr>
    @endforelse
    </tbody>
  </table>
</div>
@endsection

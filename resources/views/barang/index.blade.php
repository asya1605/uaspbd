@extends('layouts.master')
@section('title','Kelola Barang')

@section('content')
<h1 class="page-title">Kelola Barang</h1>

@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif
@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif

{{-- Form tambah --}}
<div class="card">
  <form method="POST" action="{{ route('barang.store') }}"
        style="display:grid;grid-template-columns:110px 1.5fr 1fr 1fr 110px 120px;gap:8px;align-items:center">
    @csrf
    <select name="jenis" required>
      <option value="">Jenis</option>
      @foreach(['A','B','C'] as $j)
        <option value="{{ $j }}">{{ $j }}</option>
      @endforeach
    </select>

    <input name="nama" placeholder="Nama barang" required>
    <select name="idsatuan" required>
      <option value="">Satuan</option>
      @foreach($satuan as $s)
        <option value="{{ $s->idsatuan }}">{{ $s->nama_satuan }}</option>
      @endforeach
    </select>
    <input name="harga" type="number" min="0" step="1" placeholder="Harga">
    <select name="status" required>
      <option value="1">Aktif</option>
      <option value="0">Nonaktif</option>
    </select>
    <button class="btn" type="submit">Tambah Barang</button>
  </form>
</div>

{{-- Tabel barang --}}
<div class="card">
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Jenis</th>
        <th>Nama</th>
        <th>Satuan</th>
        <th>Harga</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $b)
      <tr>
        <form method="POST" action="{{ route('barang.update',$b->idbarang) }}">
          @csrf
          <td>{{ $b->idbarang }}</td>
          <td>
            <select name="jenis">
              @foreach(['A','B','C'] as $j)
                <option value="{{ $j }}" {{ $b->jenis===$j?'selected':'' }}>{{ $j }}</option>
              @endforeach
            </select>
          </td>
          <td><input name="nama" value="{{ $b->nama }}"></td>
          <td>
            <select name="idsatuan">
              @foreach($satuan as $s)
                <option value="{{ $s->idsatuan }}" {{ $b->idsatuan==$s->idsatuan?'selected':'' }}>
                  {{ $s->nama_satuan }}
                </option>
              @endforeach
            </select>
          </td>
          <td><input type="number" name="harga" min="0" step="1" value="{{ $b->harga }}"></td>
          <td>
            <select name="status">
              <option value="1" {{ $b->status==1?'selected':'' }}>Aktif</option>
              <option value="0" {{ $b->status==0?'selected':'' }}>Nonaktif</option>
            </select>
          </td>
          <td>
            <button class="btn" type="submit">Update</button>
          </form>
          <form method="POST" action="{{ route('barang.delete',$b->idbarang) }}"
                onsubmit="return confirm('Hapus barang ini?')" style="display:inline">
            @csrf
            <button class="btn" style="background:#dc2626">Hapus</button>
          </form>
          </td>
      </tr>
      @empty
        <tr><td colspan="7">Belum ada data barang.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

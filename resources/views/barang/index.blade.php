@extends('layouts.master')
@section('title','Kelola Barang')

@section('content')
<style>
  /* 🌸 Table */
  table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Poppins', sans-serif;
    font-size: 15px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(198,124,143,0.15);
  }

  thead {
    background: linear-gradient(90deg, #fcbad3, #ffb6c1);
    color: white;
  }

  thead th {
    padding: 12px;
    text-align: left;
    font-weight: 600;
    letter-spacing: 0.4px;
  }

  tbody tr:nth-child(even) { background: #fff4f7; }
  tbody tr:hover { background: #ffe8ef; transition: 0.2s; }
  tbody td { padding: 10px 12px; color: #4b2e31; vertical-align: middle; }

  /* 🌸 Input & Select */
  input[type="text"],
  input[type="number"],
  select {
    border: 1px solid #fbc4d8;
    border-radius: 8px;
    padding: 8px 10px;
    font-size: 14px;
    background: #fff9fa;
    transition: 0.2s ease;
  }

  input:focus,
  select:focus {
    outline: none;
    border-color: #ff8fb2;
    box-shadow: 0 0 5px #ffc1d4;
  }

  /* 🌸 Buttons */
  .btn {
    border: none;
    border-radius: 8px;
    padding: 8px 14px;
    font-weight: 600;
    color: white;
    cursor: pointer;
    transition: 0.2s ease;
  }

  .btn:hover { transform: scale(1.05); }

  .btn-add { background: linear-gradient(90deg, #ff99b8, #ffaec9); }
  .btn-update { background: linear-gradient(90deg, #6ee7b7, #34d399); }
  .btn-delete { background: linear-gradient(90deg, #ff7b9e, #ff5178); }

  /* 🌸 Cards & Header */
  .card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 6px 18px rgba(198,124,143,0.15);
    border: 1px solid #ffd6e3;
    padding: 24px;
    margin-bottom: 28px;
  }

  .page-title {
    color: #c67c8f;
    text-align: center;
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
  }

  .alert-ok {
    background: #ffe8ef;
    color: #7a2e3c;
    border-left: 5px solid #f69ab3;
    padding: 10px 16px;
    border-radius: 8px;
    margin-bottom: 12px;
  }

  .alert-err {
    background: #ffe0e0;
    color: #991b1b;
    border-left: 5px solid #f87171;
    padding: 10px 16px;
    border-radius: 8px;
    margin-bottom: 12px;
  }
</style>

<h1 class="page-title">Kelola Barang 💅</h1>

@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif
@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif

{{-- 🌷 Form tambah barang --}}
<div class="card">
  <form method="POST" action="{{ route('barang.store') }}"
        style="display:grid;grid-template-columns:120px 2fr 1fr 1fr 120px 160px;gap:10px;align-items:center;">
    @csrf
    <select name="jenis" required>
      <option value="">Jenis</option>
      @foreach(['A','B','C'] as $j)
        <option value="{{ $j }}">{{ $j }}</option>
      @endforeach
    </select>

    <input name="nama" placeholder="Nama barang (mis. Serum, Toner...)" required>
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
    <button class="btn btn-add" type="submit">+ Tambah Barang</button>
  </form>
</div>

{{-- 🌷 Tabel Barang --}}
<div class="card">
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Jenis</th>
        <th>Nama Barang</th>
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
            <button class="btn btn-update" type="submit">Update</button>
        </form>
          <form method="POST" action="{{ route('barang.delete',$b->idbarang) }}"
                onsubmit="return confirm('Hapus barang ini?')" style="display:inline">
            @csrf
            <button class="btn btn-delete" style="margin-top:6px;">Hapus</button>
          </form>
          </td>
      </tr>
      @empty
        <tr><td colspan="7" align="center" style="padding:15px;">Belum ada data barang 😢</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection

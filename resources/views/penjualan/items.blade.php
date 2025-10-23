@extends('layout')
@section('title','Tambah Item Penjualan')

@section('content')
<h1 class="page-title">Tambah Item Penjualan #{{ $idpenjualan }}</h1>

@if(session('ok'))
  <div class="card" style="background:#e6ffed;border:1px solid #b6f0c2"> {{ session('ok') }} </div>
@endif

<div class="card" style="margin-bottom:14px">
  <form method="post" action="/penjualan/{{ $idpenjualan }}/items"
        style="display:flex;gap:8px;align-items:center">
    @csrf
    <select name="idbarang" required
            style="flex:1;border:1px solid #e5e7eb;border-radius:10px;padding:10px">
      @foreach($barang as $b)
        <option value="{{ $b->idbarang }}">{{ $b->nama }}</option>
      @endforeach
    </select>
    <input type="number" name="jumlah" min="1" value="1" required
           style="width:120px;border:1px solid #e5e7eb;border-radius:10px;padding:10px">
    <button class="btn" type="submit">Tambah</button>
  </form>
</div>

@if(isset($header))
<div class="card" style="margin-bottom:14px">
  <b>Ringkasan</b> —
  Subtotal: {{ number_format($header->subtotal_nilai,2) }}
  | PPN: {{ number_format($header->ppn,2) }}
  | Total: {{ number_format($header->total_nilai,2) }}
</div>
@endif

@if(isset($details) && count($details))
<div class="card">
  <table border="0" width="100%" cellpadding="10" style="border-collapse:collapse">
    <thead>
      <tr style="background:#f8fafc">
        <th align="left">Barang</th>
        <th align="left">Harga</th>
        <th align="left">Qty</th>
        <th align="left">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($details as $d)
      <tr style="border-top:1px solid #eef2f7">
        <td>{{ $d->nama }}</td>
        <td>{{ number_format($d->harga_satuan,2) }}</td>
        <td>{{ $d->jumlah }}</td>
        <td>{{ number_format($d->subtotal,2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif

<p style="margin-top:12px"><a class="btn" href="/penjualan">Selesai</a></p>
@endsection

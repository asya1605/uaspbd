@extends('layout')
@section('title','Buat Penjualan')

@section('content')
<h1 class="page-title">Buat Penjualan</h1>

<div class="card">
  <form method="post" action="/penjualan" style="display:flex;gap:8px;align-items:center">
    @csrf
    <label>User</label>
    <select name="iduser" required
            style="border:1px solid #e5e7eb;border-radius:10px;padding:10px">
      @foreach($users as $u)
        <option value="{{ $u->iduser }}">{{ $u->username }}</option>
      @endforeach
    </select>
    <button class="btn" type="submit">Buat & Tambah Item</button>
  </form>
  <p style="margin-top:10px;color:#64748b">
    Margin aktif (estimasi): {{ isset($margin->persen) ? $margin->persen*100 : 15 }}%
  </p>
</div>
@endsection

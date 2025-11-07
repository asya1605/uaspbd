@extends('layouts.master')
@section('title','Tambah Penjualan')

@section('content')
<h1 class="page-title text-pink-600">✨ Tambah Transaksi Penjualan ✨</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif
@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

{{-- 💗 Card Form Tambah Penjualan --}}
<div class="card" style="background:#fff0f5;border:2px solid #fbcfe8;">
  <form method="POST" action="{{ route('penjualan.store') }}" style="display:grid;gap:16px;">
    @csrf

    <div>
      <label class="font-semibold text-pink-700">👩‍💻 User Input</label><br>
      <select name="iduser" required
              style="width:100%;padding:10px;border:1px solid #f9a8d4;border-radius:10px;background:#fff;">
        <option value="">-- Pilih User --</option>
        @foreach($users as $u)
          <option value="{{ $u->iduser }}">{{ $u->username }}</option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="font-semibold text-pink-700">💰 Margin Penjualan</label><br>
      <select name="idmargin" required
              style="width:100%;padding:10px;border:1px solid #f9a8d4;border-radius:10px;background:#fff;">
        <option value="">-- Pilih Margin (%) --</option>
        <option value="1">10%</option>
        <option value="2">15%</option>
        <option value="3">20%</option>
      </select>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
      <div>
        <label class="font-semibold text-pink-700">🧾 Subtotal</label><br>
        <input type="number" name="subtotal" min="0" placeholder="contoh: 200000"
               style="width:100%;padding:10px;border:1px solid #f9a8d4;border-radius:10px;">
      </div>

      <div>
        <label class="font-semibold text-pink-700">💸 PPN (11%)</label><br>
        <input type="number" name="ppn" min="0" placeholder="contoh: 22000"
               style="width:100%;padding:10px;border:1px solid #f9a8d4;border-radius:10px;">
      </div>
    </div>

    <button class="btn" style="background:#ec4899;width:100%;padding:10px;border-radius:10px;">
       Simpan Penjualan
    </button>
  </form>
</div>

{{-- 💖 Riwayat Penjualan Sebelumnya --}}
@if(isset($rows) && count($rows) > 0)
<div class="card mt-6" style="background:#fffafc;border:2px solid #fbcfe8;">
  <h2 class="text-pink-700 mb-3">📜 Riwayat Penjualan Sebelumnya</h2>
  <table>
    <thead style="background:#fce7f3;">
      <tr>
        <th>ID</th>
        <th>Tanggal</th>
        <th>User</th>
        <th>Subtotal</th>
        <th>PPN</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rows as $r)
      <tr>
        <td>{{ $r->idpenjualan }}</td>
        <td>{{ $r->created_at }}</td>
        <td>{{ $r->username }}</td>
        <td>Rp {{ number_format($r->subtotal_nilai,0,',','.') }}</td>
        <td>Rp {{ number_format($r->ppn,0,',','.') }}</td>
        <td><b>Rp {{ number_format($r->total_nilai,0,',','.') }}</b></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif
@endsection

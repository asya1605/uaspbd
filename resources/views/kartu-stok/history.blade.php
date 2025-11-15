@extends('layouts.master')
@section('title','Riwayat Kartu Stok')

@section('content')
<style>
  body, select, input, button {
    font-family: 'Poppins', sans-serif;
  }

  .page-title {
    color: #c67c8f;
    text-align: center;
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
  }

  .card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 6px 18px rgba(198,124,143,0.15);
    border: 1px solid #ffd6e3;
    padding: 24px;
    margin-bottom: 28px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(198,124,143,0.1);
  }

  thead {
    background: linear-gradient(90deg, #fcbad3, #ffb6c1);
    color: white;
  }
  thead th {
    padding: 10px;
    text-align: left;
    font-weight: 600;
  }

  tbody tr:nth-child(even) { background: #fff4f7; }
  tbody tr:hover { background: #ffe8ef; transition: 0.2s; }
  tbody td { padding: 8px 10px; color: #4b2e31; }

  .btn {
    border: none;
    border-radius: 10px;
    padding: 10px 18px;
    font-weight: 600;
    color: white;
    cursor: pointer;
    transition: 0.2s;
  }
  .btn:hover { transform: scale(1.05); }
  .btn-blue { background: linear-gradient(90deg, #93c5fd, #60a5fa); }
  .btn-pink { background: linear-gradient(90deg, #f9a8d4, #f472b6); }

  /* warna teks dinamis */
  .text-green { color: #15803d; font-weight: 600; }
  .text-red { color: #dc2626; font-weight: 600; }
  .text-purple { color: #7c3aed; font-weight: 600; }
</style>

<h1 class="page-title">
  📜 Riwayat Kartu Stok<br>
  <span style="font-size:1.1rem;color:#c67c8f;">Barang: {{ $barang->nama }}</span>
</h1>

<div class="card" style="background:#fffafc;border:2px solid #fbcfe8;">
  <table>
    <thead>
      <tr>
        <th>Tipe</th>
        <th>Masuk</th>
        <th>Keluar</th>
        <th>Stok Setelah</th>
        <th>Waktu</th>
        <th>ID Transaksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($histories as $h)
        @php
          // tentukan warna teks berdasarkan jenis transaksi
          $warna = $h->jenis_transaksi == 'P'
                    ? 'text-green'
                    : ($h->jenis_transaksi == 'J'
                      ? 'text-red'
                      : 'text-purple');
        @endphp
        <tr class="{{ $warna }}">
          <td>
            @if($h->jenis_transaksi == 'P') 🟢 Penerimaan 
            @elseif($h->jenis_transaksi == 'J') 🔴 Penjualan
            @elseif($h->jenis_transaksi == 'S') 🟣 Stok Awal
            @endif
          </td>
          <td>{{ $h->masuk ?? '-' }}</td>
          <td>{{ $h->keluar ?? '-' }}</td>
          <td><b>{{ $h->stock }}</b></td>
          <td>{{ $h->create_at }}</td>
          <td>{{ $h->idtransaksi ?? '-' }}</td>
        </tr>
      @empty
        <tr><td colspan="6" align="center" style="padding:12px;">Belum ada riwayat stok 📭</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div style="margin-top:20px;text-align:center;">
  <a href="{{ route('kartustok.index') }}" class="btn btn-pink">← Kembali ke Daftar Stok</a>
</div>
@endsection

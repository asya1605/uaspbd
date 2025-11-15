@extends('layouts.master')
@section('title', 'Detail Penerimaan')

@section('content')
<style>
  .page-title {
    color: #c67c8f;
    font-weight: 700;
    font-size: 1.8rem;
    margin-bottom: 25px;
  }

  .card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 15px rgba(198,124,143,0.15);
    border: 1px solid #ffd6e3;
    padding: 25px 30px;
    margin-bottom: 25px;
  }

  .card p {
    font-size: 15px;
    color: #4b2e31;
    margin: 6px 0;
  }

  .card p b {
    color: #b44a6b;
  }

  .table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 12px;
    overflow: hidden;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
  }

  thead {
    background: linear-gradient(90deg, #fcbad3, #ffb6c1);
    color: white;
  }

  thead th {
    padding: 10px;
    text-align: center;
    font-weight: 600;
    letter-spacing: 0.4px;
  }

  tbody td {
    padding: 10px;
    border-bottom: 1px solid #ffe4ec;
    text-align: center;
    color: #4b2e31;
  }

  tbody tr:nth-child(even) {
    background: #fff5f8;
  }

  tbody tr:hover {
    background: #ffe9f0;
    transition: 0.2s ease;
  }

  .status-badge {
    display: inline-block;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 10px;
    color: white;
  }

  .status-diterima { background: #34d399; }
  .status-pending { background: #facc15; color: #4b2e31; }
  .status-gagal { background: #f87171; }

</style>

<h1 class="page-title text-center">📑 Detail Penerimaan #{{ $penerimaan->idpenerimaan }}</h1>

{{-- 🌸 Informasi Umum --}}
<div class="card">
  <p><b>Vendor:</b> {{ $penerimaan->nama_vendor }}</p>
  <p><b>User Input:</b> {{ $penerimaan->username }}</p>
  <p><b>Tanggal:</b> {{ \Carbon\Carbon::parse($penerimaan->created_at)->format('d M Y, H:i') }}</p>
  <p>
    <b>Status:</b>
    @if($penerimaan->status == 'diterima')
      <span class="status-badge status-diterima">🟢 Diterima</span>
    @elseif($penerimaan->status == 'pending')
      <span class="status-badge status-pending">🟡 Pending</span>
    @else
      <span class="status-badge status-gagal">🔴 Gagal</span>
    @endif
  </p>
</div>

{{-- 🌸 Detail Barang --}}
<div class="card">
  <h3 style="margin-bottom:12px;color:#b44a6b;font-weight:600;">Barang yang Diterima</h3>
  <table class="table">
    <thead>
      <tr>
        <th>Nama Barang</th>
        <th>Satuan</th>
        <th>Jumlah Diterima</th>
        <th>Harga Satuan</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @forelse($details as $d)
      <tr>
        <td>{{ $d->nama_barang }}</td>
        <td>{{ $d->nama_satuan }}</td>
        <td>{{ $d->jumlah_terima }}</td>
        <td>Rp{{ number_format($d->harga_satuan_terima, 0, ',', '.') }}</td>
        <td><b>Rp{{ number_format($d->sub_total_terima, 0, ',', '.') }}</b></td>
      </tr>
      @empty
      <tr>
        <td colspan="5" style="text-align:center;color:#a17c83;padding:15px;">
          Belum ada data barang diterima 😢
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div style="text-align:center;margin-top:20px;">
  <a href="{{ route('penerimaan.index') }}" class="btn-main" 
     style="background:linear-gradient(90deg,#93c5fd,#60a5fa);padding:10px 20px;border-radius:10px;color:white;font-weight:600;text-decoration:none;box-shadow:0 4px 10px rgba(96,165,250,0.35);transition:0.2s;">
     ← Kembali ke Daftar
  </a>
</div>
@endsection

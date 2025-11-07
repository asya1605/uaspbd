@extends('layouts.master')
@section('title','Laporan Margin Penjualan')

@section('content')
<h1 class="page-title">💰 Laporan Margin Penjualan 💖</h1>

@if(session('ok'))
  <div class="alert-ok">{{ session('ok') }}</div>
@endif

@if($errors->any())
  <div class="alert-err">{{ $errors->first() }}</div>
@endif

<div class="card">
  <h2 style="font-weight:600;margin-bottom:12px;color:#b44a6b;">Rekapitulasi Keuntungan Penjualan</h2>

  <table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead style="background:#ffe4ec;">
      <tr style="text-align:left;">
        <th style="padding:10px;">ID</th>
        <th style="padding:10px;">Tanggal</th>
        <th style="padding:10px;">Kasir</th>
        <th style="padding:10px;text-align:right;">Total Penjualan</th>
        <th style="padding:10px;text-align:right;">Modal</th>
        <th style="padding:10px;text-align:right;">Margin (Rp)</th>
        <th style="padding:10px;text-align:center;">Margin (%)</th>
      </tr>
    </thead>

    <tbody>
      @forelse($rows as $r)
      <tr style="border-bottom:1px solid #fdd7e0;">
        <td style="padding:8px;">{{ $r->idpenjualan }}</td>
        <td style="padding:8px;">{{ $r->tanggal_penjualan }}</td>
        <td style="padding:8px;">{{ $r->kasir }}</td>

        <td style="padding:8px;text-align:right;">
          Rp{{ number_format($r->total_penjualan ?? 0, 0, ',', '.') }}
        </td>

        <td style="padding:8px;text-align:right;">
          Rp{{ number_format($r->total_modal ?? 0, 0, ',', '.') }}
        </td>

        {{-- 🌷 Margin Nominal --}}
        <td style="padding:8px; text-align:right;" class="{{ ($r->margin ?? 0) >= 0 ? 'text-positive' : 'text-negative' }}">
          Rp{{ number_format($r->margin ?? 0, 0, ',', '.') }}
        </td>

        {{-- 🌷 Margin Persentase --}}
        <td style="padding:8px; text-align:center;" class="{{ ($r->persentase_margin ?? 0) >= 0 ? 'text-positive' : 'text-negative' }}">
          {{ number_format($r->persentase_margin ?? 0, 2) }}%
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" style="text-align:center;padding:12px;color:#a17c83;">
          Belum ada data penjualan untuk dihitung margin 💭
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- 🌸 Ringkasan Total Keseluruhan --}}
@if(count($rows) > 0)
  @php
    $totalMargin = 0;
    $totalPenjualan = 0;
    foreach ($rows as $r) {
        $totalMargin += $r->margin ?? 0;
        $totalPenjualan += $r->total_penjualan ?? 0;
    }
    $avgMargin = $totalPenjualan > 0 ? ($totalMargin / $totalPenjualan) * 100 : 0;
  @endphp

  <div class="card" style="background:#fff0f6;border:1px solid #ffd6e0;">
    <h3 style="margin-bottom:8px;color:#b33e66;">📊 Ringkasan Keseluruhan</h3>
    <p><b>Total Penjualan:</b> Rp{{ number_format($totalPenjualan, 0, ',', '.') }}</p>
    <p><b>Total Margin:</b> Rp{{ number_format($totalMargin, 0, ',', '.') }}</p>
    <p><b>Rata-rata Margin:</b> {{ number_format($avgMargin, 2) }}%</p>
  </div>
@endif
@endsection

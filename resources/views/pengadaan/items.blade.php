@extends('layouts.master')
@section('title', 'Detail Pengadaan')

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow-lg rounded-2xl p-8">
  <h1 class="text-2xl font-bold text-rose-700 mb-5 flex items-center gap-2">
    🧾 Detail Barang Pengadaan #{{ $pengadaan->idpengadaan ?? '' }}
  </h1>

  {{-- Informasi Pengadaan --}}
  @if($pengadaan)
  <div class="bg-pink-50 border-l-4 border-rose-400 px-4 py-3 mb-5 rounded">

    <p><b>Vendor:</b> {{ $pengadaan->nama_vendor }}</p>
    <p><b>User Input:</b> {{ $pengadaan->username }}</p>

    {{-- ⭐ STATUS OTOMATIS --}}
<p><b>Status:</b>
  @if($pengadaan->status === 'selesai')
    <span class="px-3 py-1 rounded-lg text-white font-semibold" style="background:#16a34a;">
      🟢 Selesai
    </span>
  @else
    <span class="px-3 py-1 rounded-lg font-semibold" style="background:#facc15;color:#4b2e31;">
      🟡 Proses
    </span>
  @endif
</p>


    <p><b>Total Barang:</b> {{ $pengadaan->total_barang ?? 0 }}</p>
    <p><b>Total Nilai:</b> Rp {{ number_format($pengadaan->total_nilai, 0, ',', '.') }}</p>
    <p><b>PPN (10%):</b> Rp {{ number_format($pengadaan->ppn, 0, ',', '.') }}</p>
    <p><b>Subtotal (Setelah PPN):</b> Rp {{ number_format($pengadaan->subtotal_nilai, 0, ',', '.') }}</p>
  </div>
  @endif

  {{-- ALERT --}}
  @if(session('ok'))
    <div class="bg-pink-50 text-rose-700 border-l-4 border-rose-400 px-4 py-2 rounded mb-4">
      {{ session('ok') }}
    </div>
  @endif
  @if($errors->any())
    <div class="bg-red-50 text-red-700 border-l-4 border-red-400 px-4 py-2 rounded mb-4">
      {{ $errors->first() }}
    </div>
  @endif

  {{-- 🌸 TABEL DETAIL --}}
  <table class="w-full border-collapse">
    <thead class="bg-rose-100 text-gray-700">
      <tr>
        <th class="p-2">ID Detail</th>
        <th>Nama Barang</th>
        <th>Harga Satuan</th>
        <th>Jumlah</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $it)
        <tr class="border-b hover:bg-pink-50">
          <td class="p-2">{{ $it->iddetail_pengadaan }}</td>
          <td>{{ $it->nama_barang }}</td>
          <td>Rp {{ number_format($it->harga_satuan, 0, ',', '.') }}</td>
          <td>{{ $it->jumlah }}</td>
          <td>Rp {{ number_format($it->sub_total, 0, ',', '.') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="5" align="center" class="p-4 text-gray-500">
            Belum ada barang dalam pengadaan ini 📭
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="mt-6 flex justify-end">
    <a href="{{ route('pengadaan.index') }}" 
       class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-lg">
      Kembali
    </a>
  </div>
</div>
@endsection

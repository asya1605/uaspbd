@extends('layouts.master')
@section('title', 'Detail Penerimaan')

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow-lg rounded-2xl p-8">
  <h1 class="text-2xl font-bold text-rose-700 mb-5 flex items-center gap-2">
    📦 Detail Penerimaan #{{ $idpenerimaan }}
  </h1>

  {{-- 🌸 RINGKASAN BARANG DITERIMA --}}
  <div class="bg-pink-50 border border-pink-200 rounded-lg p-4 mb-6">
    <h2 class="font-semibold text-rose-700 mb-2">Ringkasan Barang Diterima</h2>
    <table class="w-full border-collapse">
      <thead class="bg-rose-100 text-gray-700">
        <tr>
          <th class="p-2">Nama Barang</th>
          <th>Jumlah Diterima</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($ringkasan as $r)
          <tr class="border-b hover:bg-pink-50">
            <td class="p-2">{{ $r->nama_barang }}</td>
            <td>{{ $r->total_jumlah }}</td>
            <td>
              <button class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-lg">Return</button>
            </td>
          </tr>
        @empty
          <tr><td colspan="3" align="center" class="p-4 text-gray-500">Belum ada data barang diterima</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- 🌸 DETAIL BARANG DITERIMA --}}
  <div class="bg-white border border-pink-200 rounded-lg p-4">
    <h2 class="font-semibold text-rose-700 mb-2">Detail Barang Diterima</h2>
    <table class="w-full border-collapse">
      <thead class="bg-rose-100 text-gray-700">
        <tr>
          <th class="p-2">ID Detail</th>
          <th>ID Penerimaan</th>
          <th>Nama Barang</th>
          <th>Harga Satuan</th>
          <th>Jumlah</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $it)
          <tr class="border-b hover:bg-pink-50">
            <td class="p-2">{{ $it->iddetail_penerimaan }}</td>
            <td>{{ $it->idpenerimaan }}</td>
            <td>{{ $it->nama_barang }}</td>
            <td>Rp {{ number_format($it->harga_satuan, 0, ',', '.') }}</td>
            <td>{{ $it->jumlah }}</td>
            <td>Rp {{ number_format($it->sub_total, 0, ',', '.') }}</td>
          </tr>
        @empty
          <tr><td colspan="6" align="center" class="p-4 text-gray-500">Belum ada detail penerimaan</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6 flex justify-end">
    <a href="{{ route('penerimaan.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-lg">Kembali</a>
  </div>
</div>
@endsection

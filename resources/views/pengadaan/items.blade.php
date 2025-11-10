@extends('layouts.master')
@section('title', 'Detail Pengadaan')

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow-lg rounded-2xl p-8">
  <h1 class="text-2xl font-bold text-rose-700 mb-5 flex items-center gap-2">
    🧾 Detail Barang Pengadaan #{{ $idpengadaan ?? '' }}
  </h1>

  {{-- Informasi Pengadaan --}}
  @if($pengadaan)
  <div class="bg-pink-50 border-l-4 border-rose-400 px-4 py-3 mb-5 rounded">
    <p><b>Vendor:</b> {{ $pengadaan->nama_vendor }}</p>
    <p><b>User Input:</b> {{ $pengadaan->username }}</p>
    <p><b>Status:</b> 
      @if($pengadaan->status == '1')
        <span style="color:#16a34a;font-weight:600;">Aktif</span>
      @else
        <span style="color:#b91c1c;font-weight:600;">Nonaktif</span>
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

  {{-- 🌸 FORM TAMBAH BARANG --}}
  <form action="{{ route('pengadaan.addItem', $idpengadaan) }}" method="POST" class="mb-6">
    @csrf
    <div class="grid grid-cols-4 gap-4 mb-4">
      <div>
        <label class="font-semibold">Pilih Barang</label>
        <select id="barang" name="idbarang" class="w-full border rounded-lg p-2 border-pink-200" required>
          <option value="">-- Pilih Barang --</option>
          @foreach(DB::select('SELECT idbarang, nama, harga FROM barang ORDER BY nama') as $b)
            <option value="{{ $b->idbarang }}" data-harga="{{ $b->harga }}">
              {{ $b->nama }}
            </option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="font-semibold">Harga Satuan</label>
        <input type="number" id="harga" name="harga_satuan" class="w-full border rounded-lg p-2 bg-pink-50 border-pink-200" readonly required>
      </div>

      <div>
        <label class="font-semibold">Jumlah</label>
        <input type="number" id="jumlah" name="jumlah" min="1" value="1" class="w-full border rounded-lg p-2 border-pink-200" required>
      </div>

      <div class="flex items-end">
        <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-5 py-2 rounded-lg w-full">
          Tambah Barang
        </button>
      </div>
    </div>

    <div id="subtotalBox" class="text-right text-gray-600 font-medium mt-2 hidden">
      Subtotal: <span id="subtotalText" class="font-semibold text-rose-700">Rp 0</span>
    </div>
  </form>

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
            Belum ada barang dalam pengadaan ini
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="mt-6 flex justify-end">
    <a href="{{ route('pengadaan.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-lg">
      Kembali
    </a>
  </div>
</div>

<script>
  const barangSelect = document.getElementById('barang');
  const hargaInput = document.getElementById('harga');
  const jumlahInput = document.getElementById('jumlah');
  const subtotalBox = document.getElementById('subtotalBox');
  const subtotalText = document.getElementById('subtotalText');

  barangSelect.addEventListener('change', function() {
    const harga = this.options[this.selectedIndex].dataset.harga || 0;
    hargaInput.value = harga;
    updateSubtotal();
  });

  jumlahInput.addEventListener('input', updateSubtotal);

  function updateSubtotal() {
    const harga = parseInt(hargaInput.value) || 0;
    const jumlah = parseInt(jumlahInput.value) || 0;
    const subtotal = harga * jumlah;

    if (harga > 0 && jumlah > 0) {
      subtotalBox.classList.remove('hidden');
      subtotalText.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    } else {
      subtotalBox.classList.add('hidden');
    }
  }
</script>
@endsection

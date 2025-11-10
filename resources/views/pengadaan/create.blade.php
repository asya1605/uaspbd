@extends('layouts.master')
@section('title', 'Tambah Pengadaan')

@section('content')
<div class="max-w-7xl mx-auto bg-white shadow-lg rounded-2xl p-8">
  <h1 class="text-2xl font-bold text-rose-700 mb-5 text-center">🧾 Tambah Pengadaan Barang</h1>

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

  <form id="formPengadaan" method="POST" action="{{ route('pengadaan.store') }}">
    @csrf

    <div class="grid grid-cols-2 gap-5 mb-5">
      <div>
        <label class="font-semibold">User Input</label>
        <input type="text" value="{{ session('user')['username'] ?? 'Belum Login' }}" 
               class="w-full border rounded-lg p-2 bg-pink-50" readonly>
      </div>

      <div>
        <label class="font-semibold">Pilih Vendor</label>
        <select name="vendor_idvendor" id="vendor" required
                class="w-full border rounded-lg p-2 border-pink-200">
          <option value="">-- Pilih Vendor --</option>
          @foreach($vendors as $v)
            <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <input type="hidden" name="status" value="1">

    <div class="grid grid-cols-4 gap-4">
      <div>
        <label class="font-semibold">Pilih Barang</label>
        <select id="barang" class="w-full border rounded-lg p-2 border-pink-200">
          <option value="">-- Pilih Barang --</option>
          @foreach($barangs as $b)
            <option value="{{ $b->idbarang }}" 
                    data-harga="{{ $b->harga }}" 
                    data-satuan="{{ $b->nama_satuan }}">
                    {{ $b->nama }}
            </option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="font-semibold">Harga Barang</label>
        <input type="text" id="harga" class="w-full border rounded-lg p-2 bg-pink-50" readonly>
      </div>
      <div>
        <label class="font-semibold">Quantity</label>
        <input type="number" id="jumlah" value="1" min="1" class="w-full border rounded-lg p-2">
      </div>
      <div>
        <label class="font-semibold">Satuan</label>
        <input type="text" id="satuan" class="w-full border rounded-lg p-2 bg-pink-50" readonly>
      </div>
    </div>

    <div class="mt-5 flex space-x-3">
      <button type="button" id="addList" class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-lg">Tambah List</button>
    </div>

    <table id="listBarang" class="w-full text-left border-collapse mt-6">
      <thead class="bg-rose-100">
        <tr>
          <th class="p-2">ID Barang</th>
          <th>Satuan</th>
          <th>Nama Barang</th>
          <th>Harga</th>
          <th>Qty</th>
          <th>Subtotal</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

    <div class="mt-6 flex justify-between text-lg font-semibold text-gray-700">
      <p>Total Barang: <span id="count">0</span></p>
      <p>Total Harga: Rp <span id="subtotal">0</span></p>
      <p>PPN (10%): Rp <span id="ppn">0</span></p>
    </div>

    <input type="hidden" name="subtotal_nilai" id="inputSubtotal">
    <input type="hidden" name="list_json" id="listJson">

    <button type="submit" class="mt-6 w-full bg-pink-500 hover:bg-pink-600 text-white font-semibold py-3 rounded-lg">
      SUBMIT PENGADAAN
    </button>
  </form>
</div>

<script>
  const list = [];
  const barangSelect = document.getElementById('barang');
  const hargaInput = document.getElementById('harga');
  const jumlahInput = document.getElementById('jumlah');
  const satuanInput = document.getElementById('satuan');
  const listBody = document.querySelector('#listBarang tbody');
  const subtotalEl = document.getElementById('subtotal');
  const ppnEl = document.getElementById('ppn');
  const countEl = document.getElementById('count');
  const inputSubtotal = document.getElementById('inputSubtotal');
  const listJson = document.getElementById('listJson');

  barangSelect.addEventListener('change', function() {
    hargaInput.value = this.options[this.selectedIndex].dataset.harga || 0;
    satuanInput.value = this.options[this.selectedIndex].dataset.satuan || '';
  });

  document.getElementById('addList').addEventListener('click', () => {
    const idbarang = barangSelect.value;
    const nama = barangSelect.options[barangSelect.selectedIndex].text;
    const harga = parseInt(hargaInput.value);
    const jumlah = parseInt(jumlahInput.value);
    const satuan = satuanInput.value;
    const subtotal = harga * jumlah;

    if (!idbarang || !harga || jumlah < 1) {
      alert('Lengkapi data barang!');
      return;
    }

    list.push({ idbarang, nama, harga, jumlah, satuan, subtotal });
    renderList();
  });

  function renderList() {
    listBody.innerHTML = '';
    let total = 0;
    list.forEach((item, i) => {
      total += item.subtotal;
      listBody.innerHTML += `
        <tr class="border-b">
          <td class="p-2">${item.idbarang}</td>
          <td>${item.satuan}</td>
          <td>${item.nama}</td>
          <td>${item.harga.toLocaleString()}</td>
          <td>${item.jumlah}</td>
          <td>${item.subtotal.toLocaleString()}</td>
          <td><button type="button" onclick="hapusItem(${i})" class="bg-red-400 hover:bg-red-500 text-white px-3 py-1 rounded">Hapus</button></td>
        </tr>
      `;
    });
    const ppn = total * 0.10;
    subtotalEl.textContent = total.toLocaleString();
    ppnEl.textContent = ppn.toLocaleString();
    countEl.textContent = list.length;
    inputSubtotal.value = total;
  }

  function hapusItem(i) {
    list.splice(i, 1);
    renderList();
  }

  document.getElementById('formPengadaan').addEventListener('submit', function(e) {
    listJson.value = JSON.stringify(list);
  });
</script>
@endsection

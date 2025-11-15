@extends('layouts.master')
@section('title', 'Tambah Penjualan')

@section('content')
<style>
  body, select, input, button { font-family: 'Poppins', sans-serif; }
  .page-title {
    color:#c67c8f;
    text-align:center;
    font-size:1.8rem;
    font-weight:700;
    margin-bottom:1.5rem;
  }
  .card {
    background:#fff;
    border-radius:20px;
    box-shadow:0 6px 18px rgba(198,124,143,0.15);
    padding:24px 28px;
    border:1px solid #ffd6e3;
  }
  table {
    width:100%;
    border-collapse:collapse;
    font-size:14px;
    border-radius:12px;
    overflow:hidden;
    margin-top:10px;
  }
  thead {
    background:linear-gradient(90deg,#fcbad3,#ffb6c1);
    color:white;
  }
  th, td {
    padding:10px;
    text-align:center;
  }
  input, select {
    border:1px solid #f9a8d4;
    border-radius:8px;
    padding:6px 8px;
    width:100%;
    background:#fffafc;
  }
  .btn {
    border:none;
    border-radius:10px;
    padding:10px 16px;
    font-weight:600;
    color:white;
    cursor:pointer;
    transition:0.3s;
  }
  .btn:hover { transform:scale(1.05); }
  .btn-add {
    background:linear-gradient(90deg,#60a5fa,#3b82f6);
    box-shadow:0 4px 10px rgba(59,130,246,0.3);
    margin-top:10px;
  }
  .btn-save {
    background:linear-gradient(90deg,#ec4899,#db2777);
    width:100%;
    margin-top:20px;
    box-shadow:0 4px 12px rgba(236,72,153,0.3);
  }
  label {
    font-weight:600;
    color:#4b2e31;
    display:block;
    margin-top:10px;
  }
</style>

<h1 class="page-title">💄 Tambah Transaksi Penjualan</h1>

<div class="card">
  <form id="penjualanForm" method="POST" action="{{ route('penjualan.store') }}">
    @csrf
    <input type="hidden" name="items" id="items-json">

    {{-- 📦 Tabel Barang --}}
    <table id="table-barang">
      <thead>
        <tr>
          <th>Barang</th>
          <th>Harga</th>
          <th>Satuan</th>
          <th>Jumlah</th>
          <th>Subtotal</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="barang-body"></tbody>
    </table>

    <button type="button" class="btn btn-add" onclick="addRow()">+ Tambah Barang</button>

    {{-- 💰 Ringkasan --}}
    <div style="margin-top:20px;">
      <label>Subtotal:</label>
      <input type="text" id="subtotal" readonly>

      <label>PPN (10%):</label>
      <input type="text" id="ppn" readonly>

      <label>Total:</label>
      <input type="text" id="total" readonly>
    </div>

    <button type="submit" class="btn btn-save">💾 Simpan Penjualan</button>
  </form>
</div>

<!-- Tempat menyimpan JSON dari server secara aman supaya editor tidak menginterpretasi blade token di dalam JS -->
<div id="barang-data" data-json='@json($barang)' style="display:none"></div>

<script>
  // Ambil data JSON dari elemen tersembunyi
  const barangData = JSON.parse(document.getElementById('barang-data').dataset.json);

  const body = document.getElementById('barang-body');
  const subtotalField = document.getElementById('subtotal');
  const ppnField = document.getElementById('ppn');
  const totalField = document.getElementById('total');
  const itemsJson = document.getElementById('items-json');

  function addRow() {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>
        <select class="barang-select" onchange="updateHarga(this)">
          <option value="">-- Pilih Barang --</option>
          ${barangData.map(b => `
            <option value="${b.idbarang}" data-harga="${b.harga}" data-satuan="${b.nama_satuan}">
              ${b.nama_barang}
            </option>`).join('')}
        </select>
      </td>
      <td><input type="number" class="harga" readonly></td>
      <td><input type="text" class="satuan" readonly></td>
      <td><input type="number" class="jumlah" min="1" value="1" onchange="updateSubtotal(this)"></td>
      <td><input type="number" class="subtotal" readonly></td>
      <td><button type="button" onclick="removeRow(this)" class="btn" style="background:#f87171;">❌</button></td>
    `;
    body.appendChild(row);
  }

  function removeRow(btn) {
    btn.closest('tr').remove();
    updateTotal();
  }

  function updateHarga(select) {
    const harga = select.selectedOptions[0]?.dataset.harga || 0;
    const satuan = select.selectedOptions[0]?.dataset.satuan || '';
    const row = select.closest('tr');
    row.querySelector('.harga').value = harga;
    row.querySelector('.satuan').value = satuan;
    updateSubtotal(row.querySelector('.jumlah'));
  }

  function updateSubtotal(input) {
    const row = input.closest('tr');
    const harga = parseFloat(row.querySelector('.harga').value) || 0;
    const qty = parseFloat(input.value) || 0;
    row.querySelector('.subtotal').value = harga * qty;
    updateTotal();
  }

  function updateTotal() {
    let subtotal = 0;
    document.querySelectorAll('.subtotal').forEach(el => subtotal += parseFloat(el.value) || 0);
    const ppn = subtotal * 0.10;
    const total = subtotal + ppn;

    subtotalField.value = subtotal.toLocaleString('id-ID');
    ppnField.value = ppn.toLocaleString('id-ID');
    totalField.value = total.toLocaleString('id-ID');

    const items = [];
    document.querySelectorAll('#barang-body tr').forEach(tr => {
      const idbarang = tr.querySelector('.barang-select').value;
      const jumlah = tr.querySelector('.jumlah').value;
      if (idbarang && jumlah > 0) items.push({ idbarang, jumlah });
    });
    itemsJson.value = JSON.stringify(items);
  }
</script>
@endsection

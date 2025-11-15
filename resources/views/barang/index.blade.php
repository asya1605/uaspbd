@extends('layouts.master')
@section('title', 'Kelola Barang')

@section('content')
<style>
  .page-wrapper {
    background: linear-gradient(to bottom right, #fff5f8, #ffe9f0);
    border-radius: 24px;
    padding: 30px 40px;
    box-shadow: 0 6px 18px rgba(198,124,143,0.1);
    margin: 0 auto;
    max-width: 1100px;
  }

  .page-header {
    text-align: center;
    margin-bottom: 25px;
  }

  .page-header h2 {
    font-family: 'Poppins', sans-serif;
    font-size: 1.8rem;
    font-weight: 700;
    color: #2e3241;
  }

  .filter-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffeef3;
    border-radius: 16px;
    padding: 10px 18px;
    box-shadow: inset 0 0 5px rgba(255, 150, 180, 0.15);
    flex-wrap: wrap;
  }

  .filter-left {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .filter-left label {
    font-weight: 600;
    color: #4b2e31;
  }

  .filter-select {
    padding: 8px 12px;
    border: 1px solid #f3b6c3;
    border-radius: 10px;
    background: #fff;
    color: #4b2e31;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
  }

  .btn-add {
    background: #ff2e76;
    color: white;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 10px;
    border: none;
    box-shadow: 0 4px 10px rgba(255, 46, 118, 0.3);
    transition: 0.2s;
  }

  .btn-add:hover { background: #ff4e8f; transform: translateY(-2px); }

  .alert {
    padding: 10px 16px;
    border-radius: 8px;
    margin-top: 15px;
    font-weight: 500;
  }

  .alert-ok { background:#e9fff1;color:#065f46;border-left:5px solid #10b981; }
  .alert-err { background:#ffe8ef;color:#7a2e3c;border-left:5px solid #f69ab3; }

  .form-section {
    display: none;
    margin-top: 20px;
    background: #fff;
    border-radius: 16px;
    padding: 25px 30px;
    box-shadow: 0 3px 10px rgba(198,124,143,0.1);
  }

  .form-section.active { display: block; }

  form label {
    font-weight: 600;
    color: #3e3e3e;
    display: block;
    margin-bottom: 5px;
  }

  form input, form select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #f8cadd;
    border-radius: 8px;
    font-size: 15px;
    background: #fffafc;
  }

  .btn-submit {
    background: #ff2e76;
    color: white;
    font-weight: 600;
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: none;
    margin-top: 10px;
    box-shadow: 0 5px 10px rgba(255, 46, 118, 0.25);
    transition: 0.3s;
  }

  .btn-submit:hover { background: #ff4e8f; transform: translateY(-1px); }

  .table-box {
    background: #fff;
    border-radius: 16px;
    padding: 20px 25px;
    box-shadow: 0 3px 10px rgba(198,124,143,0.1);
    margin-top: 25px;
    overflow-x: auto;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Poppins', sans-serif;
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

  tbody td {
    padding: 10px;
    border-bottom: 1px solid #ffe0eb;
  }

  tbody tr:nth-child(even) { background: #fff6f8; }

  .btn {
    padding: 6px 12px;
    border-radius: 8px;
    color: white;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: 0.2s;
  }

  .btn-update { background: #34d399; }
  .btn-delete { background: #ff5178; margin-left: 4px; }
  .btn-update:hover, .btn-delete:hover { transform: scale(1.05); }
</style>

<div class="page-wrapper">
  <div class="page-header">
    <h2>Daftar Barang 🛍️</h2>
  </div>

  <div class="filter-bar">
    <div class="filter-left">
      <label for="filter">Tampilkan:</label>
      <select id="filter" class="filter-select" onchange="filterBarang(this.value)">
        <option value="aktif" {{ ($filter ?? 'aktif') === 'aktif' ? 'selected' : '' }}>Barang Aktif</option>
        <option value="all" {{ ($filter ?? '') === 'all' ? 'selected' : '' }}>Semua Barang</option>
      </select>
    </div>
    <button class="btn-add" id="btnAdd">+ Tambah Barang</button>
  </div>

  @if(session('ok')) <div class="alert alert-ok">{{ session('ok') }}</div> @endif
  @if($errors->any()) <div class="alert alert-err">{{ $errors->first() }}</div> @endif

  <div id="form-section" class="form-section">
    <h3 class="text-center text-lg font-bold text-[#344565] mb-5" id="form-title">Form Tambah Barang</h3>
    <form id="barangForm" method="POST" action="{{ route('barang.store') }}">
      @csrf
      <input type="hidden" id="edit_id" name="idbarang">

      <div class="form-group">
        <label>Jenis Barang</label>
        <input type="text" name="jenis" id="jenis" required>
      </div>

      <div class="form-group">
        <label>Nama Barang</label>
        <input type="text" name="nama" id="nama" required>
      </div>

      <div class="form-group">
        <label>Satuan</label>
        <select name="idsatuan" id="idsatuan" required>
          <option value="">Pilih Satuan</option>
          @foreach($satuan as $s)
            <option value="{{ $s->idsatuan }}">{{ $s->nama_satuan }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label>Harga</label>
        <input type="number" name="harga" id="harga" required>
      </div>

      <div class="form-group" id="status-group" style="display:none;">
        <label>Status Barang</label>
        <select name="status" id="status">
          <option value="1">🟢 Aktif</option>
          <option value="0">⚫ Nonaktif</option>
        </select>
      </div>

      <button type="submit" class="btn-submit" id="submitBtn">SIMPAN BARANG</button>
    </form>
  </div>

  <div class="table-box">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Jenis</th>
          <th>Nama Barang</th>
          <th>Satuan</th>
          <th>Harga</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rows as $b)
        <tr>
          <td>{{ $b->idbarang }}</td>
          <td>{{ $b->jenis }}</td>
          <td>{{ $b->nama }}</td>
          <td>{{ $b->nama_satuan }}</td>
          <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
          <td>
            @if($b->status == 1)
              <span style="color:#16a34a;font-weight:600;">🟢 Aktif</span>
            @else
              <span style="color:#a3a3a3;font-weight:600;">⚫ Nonaktif</span>
            @endif
          </td>
          <td>
            <button class="btn btn-update"
              onclick="editBarang('{{ $b->idbarang }}', '{{ $b->jenis }}', '{{ $b->nama }}', '{{ $b->idsatuan }}', '{{ $b->harga }}', '{{ $b->status }}')">Edit</button>

            <form action="{{ route('barang.delete', $b->idbarang) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus barang ini?')">
              @csrf
              <button type="submit" class="btn btn-delete">Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:15px;">Belum ada data barang 😢</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<script>
  function filterBarang(value) {
    window.location.href = `{{ route('barang.index') }}?filter=${value}`;
  }

  const btnAdd = document.getElementById('btnAdd');
  const formSection = document.getElementById('form-section');
  const formTitle = document.getElementById('form-title');
  const submitBtn = document.getElementById('submitBtn');
  const barangForm = document.getElementById('barangForm');
  const statusGroup = document.getElementById('status-group');

  btnAdd.addEventListener('click', () => {
    formSection.classList.toggle('active');
    resetForm();
  });

  function editBarang(id, jenis, nama, satuan, harga, status) {
    formSection.classList.add('active');
    formTitle.textContent = "Form Edit Barang";
    submitBtn.textContent = "UPDATE BARANG";
    barangForm.action = `/barang/${id}/update`;
    document.getElementById('edit_id').value = id;
    document.getElementById('jenis').value = jenis;
    document.getElementById('nama').value = nama;
    document.getElementById('idsatuan').value = satuan;
    document.getElementById('harga').value = harga;
    statusGroup.style.display = 'block';
    document.getElementById('status').value = status;
  }

  function resetForm() {
    formTitle.textContent = "Form Tambah Barang";
    submitBtn.textContent = "SIMPAN BARANG";
    barangForm.action = "{{ route('barang.store') }}";
    barangForm.reset();
    statusGroup.style.display = 'none';
  }
</script>
@endsection

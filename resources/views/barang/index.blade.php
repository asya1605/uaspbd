@extends('layouts.master')
@section('title', 'Kelola Barang')

@section('content')
<style>
  /* 🌸 Wrapper */
  .container {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 18px rgba(198,124,143,0.15);
    padding: 30px 40px;
    max-width: 1100px;
    margin: 0 auto;
  }

  /* 🌸 Header Tabs */
  .header-tabs {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #f1d3dd;
    padding-bottom: 12px;
  }

  .header-tabs h2 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #344565;
  }

  .tab-buttons {
    display: flex;
    gap: 10px;
    background: #f9f6f8;
    border-radius: 12px;
    overflow: hidden;
  }

  .tab-btn {
    padding: 8px 22px;
    font-weight: 600;
    border: none;
    background: none;
    cursor: pointer;
    color: #344565;
    transition: all 0.25s;
  }

  .tab-btn.active {
    background: white;
    color: #c67c8f;
    box-shadow: inset 0 -3px 0 #c67c8f;
  }

  /* 🌸 Form */
  .form-section {
    margin-top: 30px;
    display: none;
  }

  .form-section.active {
    display: block;
  }

  form input, form select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #f8cadd;
    border-radius: 8px;
    font-size: 15px;
    background: #fffafc;
  }

  form label {
    font-weight: 600;
    color: #3e3e3e;
    display: block;
    margin-bottom: 5px;
  }

  form .form-group {
    margin-bottom: 18px;
  }

  .btn-submit {
    background: #ff2e76;
    color: white;
    font-weight: 600;
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: none;
    box-shadow: 0 5px 10px rgba(255, 46, 118, 0.25);
    transition: 0.3s;
  }

  .btn-submit:hover {
    background: #ff4e8f;
    transform: translateY(-1px);
  }

  /* 🌸 Table */
  table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 14px;
    overflow: hidden;
    margin-top: 25px;
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
    background: white;
    border-bottom: 1px solid #ffe0eb;
  }

  tbody tr:nth-child(even) {
    background: #fff6f8;
  }

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

<div class="container">

  {{-- 🌸 Header Tabs --}}
  <div class="header-tabs">
    <h2>Manage Barang</h2>
    <div class="tab-buttons">
      <button id="tab-create" class="tab-btn active">Create</button>
      <button id="tab-table" class="tab-btn">Table</button>
    </div>
  </div>

  {{-- 🌸 Create / Edit Form --}}
  <div id="form-section" class="form-section active">
    <h3 class="text-center text-[20px] font-bold text-[#344565] mb-5" id="form-title">Form Input Barang</h3>

    <form id="barangForm" method="POST" action="{{ route('barang.store') }}">
      @csrf
      <input type="hidden" id="edit_id" name="idbarang">

      <div class="form-group">
        <label>Jenis Barang</label>
        <input type="text" name="jenis" id="jenis" placeholder="Enter jenis barang" required>
      </div>

      <div class="form-group">
        <label>Nama Barang</label>
        <input type="text" name="nama" id="nama" placeholder="Enter nama barang" required>
      </div>

      <div class="form-group">
        <label>Satuan</label>
        <select name="idsatuan" id="idsatuan" required>
          <option value="">Select Satuan</option>
          @foreach($satuan as $s)
            <option value="{{ $s->idsatuan }}">{{ $s->nama_satuan }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label>Harga</label>
        <input type="number" name="harga" id="harga" placeholder="Enter harga barang" required>
      </div>

      <button type="submit" class="btn-submit" id="submitBtn">ADD BARANG</button>
    </form>
  </div>

  {{-- 🌸 Table Section --}}
  <div id="table-section" class="form-section">
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
        @foreach($rows as $b)
        <tr>
          <td>{{ $b->idbarang }}</td>
          <td>{{ $b->jenis }}</td>
          <td>{{ $b->nama }}</td>
          <td>{{ $b->nama_satuan }}</td>
          <td>{{ number_format($b->harga) }}</td>
          <td>{{ $b->status ? 'Aktif' : 'Nonaktif' }}</td>
          <td>
            <button class="btn btn-update"
                    onclick="editBarang('{{ $b->idbarang }}','{{ $b->jenis }}','{{ $b->nama }}','{{ $b->idsatuan }}','{{ $b->harga }}')">Edit</button>
            <form action="{{ route('barang.delete', $b->idbarang) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus barang ini?')">
              @csrf
              <button type="submit" class="btn btn-delete">Hapus</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

</div>

<script>
  // 🌸 Auto detect barang duplikat
  const jenisInput = document.getElementById('jenis');
  const namaInput  = document.getElementById('nama');
  const hargaInput = document.getElementById('harga');

  [jenisInput, namaInput, hargaInput].forEach(input => {
    input.addEventListener('input', detectBarang);
  });

  let timeout = null;

  function detectBarang() {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
      const jenis = jenisInput.value.trim();
      const nama  = namaInput.value.trim();
      const harga = hargaInput.value.trim();

      if (jenis && nama && harga) {
        fetch(`{{ route('barang.check') }}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ jenis, nama, harga })
        })
        .then(res => res.json())
        .then(data => {
          if (data.found) {
            showToast(`⚠️ Barang "${nama}" sudah terdaftar (Satuan: ${data.data.nama_satuan})`);
            document.getElementById('idsatuan').value = data.data.idsatuan;
            document.getElementById('edit_id').value = data.data.idbarang;
          }
        })
        .catch(err => console.error(err));
      }
    }, 500); // delay 0.5 detik
  }

  // 🌷 Toast alert lembut
  function showToast(message) {
    let toast = document.createElement('div');
    toast.textContent = message;
    toast.style.position = 'fixed';
    toast.style.bottom = '30px';
    toast.style.right = '30px';
    toast.style.background = '#ffb6c1';
    toast.style.color = '#4b2e31';
    toast.style.padding = '10px 18px';
    toast.style.borderRadius = '10px';
    toast.style.boxShadow = '0 4px 10px rgba(198,124,143,0.25)';
    toast.style.zIndex = '9999';
    toast.style.fontWeight = '600';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3500);
  }

  // 🌸 Tab control
  const tabCreate = document.getElementById('tab-create');
  const tabTable = document.getElementById('tab-table');
  const formSection = document.getElementById('form-section');
  const tableSection = document.getElementById('table-section');
  const formTitle = document.getElementById('form-title');
  const submitBtn = document.getElementById('submitBtn');
  const barangForm = document.getElementById('barangForm');

  tabCreate.addEventListener('click', () => {
    tabCreate.classList.add('active');
    tabTable.classList.remove('active');
    formSection.classList.add('active');
    tableSection.classList.remove('active');
    resetForm();
  });

  tabTable.addEventListener('click', () => {
    tabTable.classList.add('active');
    tabCreate.classList.remove('active');
    tableSection.classList.add('active');
    formSection.classList.remove('active');
  });

  function editBarang(id, jenis, nama, satuan, harga) {
    tabCreate.click();
    formTitle.textContent = "Form Edit Barang";
    submitBtn.textContent = "UPDATE BARANG";
    barangForm.action = `/barang/${id}/update`;
    document.getElementById('edit_id').value = id;
    document.getElementById('jenis').value = jenis;
    document.getElementById('nama').value = nama;
    document.getElementById('idsatuan').value = satuan;
    document.getElementById('harga').value = harga;
  }

  function resetForm() {
    formTitle.textContent = "Form Input Barang";
    submitBtn.textContent = "ADD BARANG";
    barangForm.action = "{{ route('barang.store') }}";
    barangForm.reset();
  }
</script>
@endsection

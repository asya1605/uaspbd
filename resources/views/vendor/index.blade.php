@extends('layouts.master')
@section('title', 'Kelola Vendor')

@section('content')
<style>
  .container {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 18px rgba(198,124,143,0.15);
    padding: 30px 40px;
    max-width: 1100px;
    margin: 0 auto;
  }

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
    transition: 0.25s;
  }

  .tab-btn.active {
    background: white;
    color: #c67c8f;
    box-shadow: inset 0 -3px 0 #c67c8f;
  }

  .form-section {
    margin-top: 30px;
    display: none;
  }

  .form-section.active { display: block; }

  form input, form select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #f8cadd;
    border-radius: 8px;
    font-size: 15px;
    background: #fffafc;
  }

  form label { font-weight: 600; margin-bottom: 5px; display: block; color: #3e3e3e; }

  .btn-submit {
    background: #ff2e76;
    color: white;
    font-weight: 600;
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: none;
    box-shadow: 0 5px 10px rgba(255,46,118,0.25);
    transition: 0.3s;
  }

  .btn-submit:hover { background: #ff4e8f; transform: translateY(-1px); }

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

  thead th { padding: 10px; text-align: left; font-weight: 600; }
  tbody td { padding: 10px; background: white; border-bottom: 1px solid #ffe0eb; }
  tbody tr:nth-child(even) { background: #fff6f8; }

  .btn { padding: 6px 12px; border-radius: 8px; color: white; font-weight: 600; border: none; cursor: pointer; transition: 0.2s; }
  .btn-update { background: #34d399; }
  .btn-delete { background: #ff5178; margin-left: 4px; }
  .btn-update:hover, .btn-delete:hover { transform: scale(1.05); }
</style>

<div class="container">

  {{-- 🌸 Header Tabs --}}
  <div class="header-tabs">
    <h2>Manage Vendor</h2>
    <div class="tab-buttons">
      <button id="tab-create" class="tab-btn active">Create</button>
      <button id="tab-table" class="tab-btn">Table</button>
    </div>
  </div>

  {{-- 🌸 Create / Edit Form --}}
  <div id="form-section" class="form-section active">
    <h3 class="text-center text-[20px] font-bold text-[#344565] mb-5" id="form-title">Form Input Vendor</h3>

    <form id="vendorForm" method="POST" action="{{ route('vendor.store') }}">
      @csrf
      <input type="hidden" id="edit_id" name="idvendor">

      <div class="form-group">
        <label>Nama Vendor</label>
        <input type="text" name="nama_vendor" id="nama_vendor" placeholder="Masukkan nama vendor" required>
      </div>

      <div class="form-group">
        <label>Badan Hukum</label>
        <select name="badan_hukum" id="badan_hukum" required>
          <option value="">Pilih Jenis</option>
          <option value="Y">Badan Usaha (Y)</option>
          <option value="N">Perorangan (N)</option>
        </select>
      </div>

      <div class="form-group">
        <label>Status</label>
        <select name="status" id="status" required>
          <option value="1">Aktif</option>
          <option value="0">Nonaktif</option>
        </select>
      </div>

      <button type="submit" class="btn-submit" id="submitBtn">ADD VENDOR</button>
    </form>
  </div>

  {{-- 🌸 Table --}}
  <div id="table-section" class="form-section">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama Vendor</th>
          <th>Badan Hukum</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($rows as $v)
        <tr>
          <td>{{ $v->idvendor }}</td>
          <td>{{ $v->nama_vendor }}</td>
          <td>{{ $v->badan_hukum == 'Y' ? 'Badan Usaha' : 'Perorangan' }}</td>
          <td>{{ $v->status ? 'Aktif' : 'Nonaktif' }}</td>
          <td>
            <button class="btn btn-update"
                    onclick="editVendor('{{ $v->idvendor }}','{{ $v->nama_vendor }}','{{ $v->badan_hukum }}','{{ $v->status }}')">Edit</button>
            <form action="{{ route('vendor.delete', $v->idvendor) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus vendor ini?')">
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
  const tabCreate = document.getElementById('tab-create');
  const tabTable = document.getElementById('tab-table');
  const formSection = document.getElementById('form-section');
  const tableSection = document.getElementById('table-section');
  const formTitle = document.getElementById('form-title');
  const submitBtn = document.getElementById('submitBtn');
  const vendorForm = document.getElementById('vendorForm');

  // 🌸 Switch tab
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

  // 🌸 Edit vendor
  function editVendor(id, nama, hukum, status) {
    tabCreate.click();
    formTitle.textContent = "Form Edit Vendor";
    submitBtn.textContent = "UPDATE VENDOR";
    vendorForm.action = `/vendor/${id}/update`;
    document.getElementById('edit_id').value = id;
    document.getElementById('nama_vendor').value = nama;
    document.getElementById('badan_hukum').value = hukum;
    document.getElementById('status').value = status;
  }

  // 🌸 Reset ke mode create
  function resetForm() {
    formTitle.textContent = "Form Input Vendor";
    submitBtn.textContent = "ADD VENDOR";
    vendorForm.action = "{{ route('vendor.store') }}";
    vendorForm.reset();
  }

  // 🌸 Auto-detect vendor duplikat
  const namaInput = document.getElementById('nama_vendor');
  const badanInput = document.getElementById('badan_hukum');

  [namaInput, badanInput].forEach(input => {
    input.addEventListener('input', detectVendor);
  });

  let timeout = null;

  function detectVendor() {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
      const nama_vendor = namaInput.value.trim();
      const badan_hukum = badanInput.value.trim();

      if (nama_vendor && badan_hukum) {
        fetch(`{{ route('vendor.check') }}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ nama_vendor, badan_hukum })
        })
        .then(res => res.json())
        .then(data => {
          if (data.found) {
            showToast(`⚠️ Vendor "${nama_vendor}" sudah terdaftar.`);
            document.getElementById('status').value = data.data.status;
            document.getElementById('edit_id').value = data.data.idvendor;
          }
        })
        .catch(err => console.error(err));
      }
    }, 500);
  }

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
</script>
@endsection

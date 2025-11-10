@extends('layouts.master')
@section('title', 'Kelola Margin Penjualan')

@section('content')
<style>
  .container {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 18px rgba(198,124,143,0.15);
    padding: 30px 40px;
    max-width: 900px;
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
    transition: all 0.25s;
  }
  .tab-btn.active {
    background: white;
    color: #c67c8f;
    box-shadow: inset 0 -3px 0 #c67c8f;
  }

  .form-section { margin-top: 30px; display: none; }
  .form-section.active { display: block; }

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

  form .form-group { margin-bottom: 18px; }

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
  .btn-submit:hover { background: #ff4e8f; transform: translateY(-1px); }

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

  tbody tr:nth-child(even) { background: #fff6f8; }

  .btn { padding: 6px 12px; border-radius: 8px; color: white; font-weight: 600; border: none; cursor: pointer; transition: 0.2s; }
  .btn-update { background: #34d399; }
  .btn-delete { background: #ff5178; margin-left: 4px; }
  .btn-update:hover, .btn-delete:hover { transform: scale(1.05); }
</style>

<div class="container">
  {{-- 🌸 Header Tabs --}}
  <div class="header-tabs">
    <h2>Kelola Margin Penjualan 💰</h2>
    <div class="tab-buttons">
      <button id="tab-create" class="tab-btn active">Create</button>
      <button id="tab-table" class="tab-btn">Table</button>
    </div>
  </div>

  {{-- 🌸 Form Input --}}
  <div id="form-section" class="form-section active">
    <h3 class="text-center text-[20px] font-bold text-[#344565] mb-5" id="form-title">Form Input Margin Penjualan</h3>

    @if(session('ok'))
      <div style="background:#e9fff1;color:#065f46;border-left:5px solid #10b981;padding:10px 16px;border-radius:8px;margin-bottom:12px;">
        {{ session('ok') }}
      </div>
    @endif
    @if($errors->any())
      <div style="background:#ffe8ef;color:#7a2e3c;border-left:5px solid #f69ab3;padding:10px 16px;border-radius:8px;margin-bottom:12px;">
        {{ $errors->first() }}
      </div>
    @endif

    <form id="marginForm" method="POST" action="{{ route('margin.store') }}">
      @csrf
      <input type="hidden" id="edit_id" name="idmargin">

      <div class="form-group">
        <label>Persentase Margin (%)</label>
        <input type="number" step="0.01" name="persentase" id="persentase" placeholder="Masukkan persentase margin (mis. 10 atau 15.5)" required>
      </div>

      <div class="form-group">
        <label>Status</label>
        <select name="status" id="status" required>
          <option value="1">Aktif</option>
          <option value="0">Nonaktif</option>
        </select>
      </div>

      <button type="submit" class="btn-submit" id="submitBtn">ADD MARGIN</button>
    </form>
  </div>

  {{-- 🌸 Table Section --}}
  <div id="table-section" class="form-section">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Persentase Margin (%)</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rows as $r)
        <tr>
          <td>{{ $r->idmargin }}</td>
          <td>{{ number_format($r->persentase, 2) }}</td>
          <td>{{ $r->status == 1 ? 'Aktif' : 'Nonaktif' }}</td>
          <td>
            <button class="btn btn-update"
              onclick="editMargin('{{ $r->idmargin }}', '{{ $r->persentase }}', '{{ $r->status }}')">Edit</button>

            <form action="{{ route('margin.delete', $r->idmargin) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus data margin ini?')">
              @csrf
              <button type="submit" class="btn btn-delete">Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4" style="text-align:center;padding:15px;">Belum ada data margin 😢</td>
        </tr>
        @endforelse
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
  const marginForm = document.getElementById('marginForm');

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

  function editMargin(id, persentase, status) {
    tabCreate.click();
    formTitle.textContent = "Form Edit Margin Penjualan";
    submitBtn.textContent = "UPDATE MARGIN";
    marginForm.action = `/margin-penjualan/${id}/update`;

    document.getElementById('edit_id').value = id;
    document.getElementById('persentase').value = persentase;
    document.getElementById('status').value = status;
  }

  function resetForm() {
    formTitle.textContent = "Form Input Margin Penjualan";
    submitBtn.textContent = "ADD MARGIN";
    marginForm.action = "{{ route('margin.store') }}";
    marginForm.reset();
  }
</script>
@endsection

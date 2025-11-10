@extends('layouts.master')
@section('title', 'Kelola User')

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
  .form-section.active { display: block; }

  form input, form select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #f8cadd;
    border-radius: 8px;
    font-size: 15px;
    background: #fffafc;
  }
  form label { font-weight: 600; color: #3e3e3e; display: block; margin-bottom: 5px; }
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
  <div class="header-tabs">
    <h2>Kelola User 👩🏻‍💻</h2>
    <div class="tab-buttons">
      <button id="tab-create" class="tab-btn active">Create</button>
      <button id="tab-table" class="tab-btn">Table</button>
    </div>
  </div>

  {{-- 🌸 Form Section --}}
  <div id="form-section" class="form-section active">
    <h3 class="text-center text-[20px] font-bold text-[#344565] mb-5" id="form-title">Form Input User</h3>

    <form id="userForm" method="POST" action="{{ route('users.store') }}">
      @csrf
      <input type="hidden" id="edit_id" name="iduser">

      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" id="username" placeholder="Masukkan username" required>
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" id="email" placeholder="Masukkan email (opsional)">
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" id="password" placeholder="Masukkan password (minimal 4 karakter)">
      </div>

      <div class="form-group">
        <label>Role</label>
        <select name="idrole" id="idrole" required>
          <option value="">Pilih role</option>
          @foreach($roles as $r)
            <option value="{{ $r->idrole }}">{{ $r->nama_role }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label>Status</label>
        <select name="status" id="status" required>
          <option value="1">Aktif</option>
          <option value="0">Nonaktif</option>
        </select>
      </div>

      <button type="submit" class="btn-submit" id="submitBtn">ADD USER</button>
    </form>
  </div>

  {{-- 🌸 Table Section --}}
  <div id="table-section" class="form-section">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Role</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($rows as $u)
        <tr>
          <td>{{ $u->iduser }}</td>
          <td>{{ $u->username }}</td>
          <td>{{ $u->email }}</td>
          <td>{{ $u->nama_role }}</td>
          <td>{{ $u->status == 1 ? 'Aktif' : 'Nonaktif' }}</td>
          <td>
            <button class="btn btn-update"
              onclick="editUser('{{ $u->iduser }}', '{{ $u->username }}', '{{ $u->email }}', '{{ $u->idrole }}', '{{ $u->status }}')">Edit</button>

            <form action="{{ route('users.delete', $u->iduser) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus user ini?')">
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
  const userForm = document.getElementById('userForm');

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

  function editUser(id, username, email, idrole, status) {
    tabCreate.click();
    formTitle.textContent = "Form Edit User";
    submitBtn.textContent = "UPDATE USER";
    userForm.action = `/users/${id}/update`;

    document.getElementById('edit_id').value = id;
    document.getElementById('username').value = username;
    document.getElementById('email').value = email;
    document.getElementById('idrole').value = idrole;
    document.getElementById('status').value = status;
  }

  function resetForm() {
    formTitle.textContent = "Form Input User";
    submitBtn.textContent = "ADD USER";
    userForm.action = "{{ route('users.store') }}";
    userForm.reset();
  }
</script>
@endsection

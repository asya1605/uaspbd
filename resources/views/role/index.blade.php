@extends('layouts.master')
@section('title', 'Kelola Role')

@section('content')
<style>
  .page-wrapper {
    background: linear-gradient(to bottom right, #fff5f8, #ffe9f0);
    border-radius: 24px;
    padding: 30px 40px;
    box-shadow: 0 6px 18px rgba(198,124,143,0.1);
    margin: 0 auto;
    max-width: 900px;
  }

  .page-header { text-align: center; margin-bottom: 25px; }
  .page-header h2 { font-family: 'Poppins', sans-serif; font-size: 1.8rem; font-weight: 700; color: #2e3241; }

  .btn-add {
    background: #ff2e76; color: white; font-weight: 600;
    padding: 10px 20px; border-radius: 10px; border: none;
    box-shadow: 0 4px 10px rgba(255,46,118,0.3); transition: 0.2s;
    display: block; margin: 0 auto; margin-top: 10px;
  }
  .btn-add:hover { background: #ff4e8f; transform: translateY(-2px); }

  .alert { padding: 10px 16px; border-radius: 8px; margin-top: 15px; font-weight: 500; }
  .alert-ok { background:#e9fff1;color:#065f46;border-left:5px solid #10b981; }
  .alert-err { background:#ffe8ef;color:#7a2e3c;border-left:5px solid #f69ab3; }

  .form-section {
    display: none; margin-top: 20px; background: #fff;
    border-radius: 16px; padding: 25px 30px;
    box-shadow: 0 3px 10px rgba(198,124,143,0.1);
  }
  .form-section.active { display: block; }

  form label { font-weight: 600; color: #3e3e3e; display: block; margin-bottom: 5px; }
  form input {
    width: 100%; padding: 10px 12px; border: 1px solid #f8cadd;
    border-radius: 8px; font-size: 15px; background: #fffafc;
  }

  .btn-submit {
    background: #ff2e76; color: white; font-weight: 600;
    width: 100%; padding: 12px; border-radius: 10px; border: none;
    margin-top: 10px; box-shadow: 0 5px 10px rgba(255,46,118,0.25);
    transition: 0.3s;
  }
  .btn-submit:hover { background: #ff4e8f; transform: translateY(-1px); }

  .table-box {
    background: #fff; border-radius: 16px;
    padding: 20px 25px; box-shadow: 0 3px 10px rgba(198,124,143,0.1);
    margin-top: 25px; overflow-x: auto;
  }

  table { width: 100%; border-collapse: collapse; font-family: 'Poppins', sans-serif; }
  thead { background: linear-gradient(90deg, #fcbad3, #ffb6c1); color: white; }
  thead th { padding: 10px; text-align: left; font-weight: 600; }
  tbody td { padding: 10px; border-bottom: 1px solid #ffe0eb; }
  tbody tr:nth-child(even) { background: #fff6f8; }

  .btn { padding: 6px 12px; border-radius: 8px; color: white; font-weight: 600; border: none; cursor: pointer; transition: 0.2s; }
  .btn-update { background: #34d399; }
  .btn-delete { background: #ff5178; margin-left: 4px; }
  .btn-update:hover, .btn-delete:hover { transform: scale(1.05); }
</style>

<div class="page-wrapper">
  <div class="page-header">
    <h2>Daftar Role 💼</h2>
  </div>

  <button class="btn-add" id="btnAdd">+ Tambah Role</button>

  {{-- 🌸 Alert --}}
  @if(session('ok')) <div class="alert alert-ok">{{ session('ok') }}</div> @endif
  @if($errors->any()) <div class="alert alert-err">{{ $errors->first() }}</div> @endif

  {{-- 🌸 Form --}}
  <div id="form-section" class="form-section">
    <h3 class="text-center text-lg font-bold text-[#344565] mb-5" id="form-title">Form Tambah Role</h3>
    <form id="roleForm" method="POST" action="{{ route('role.store') }}">
      @csrf
      <input type="hidden" id="edit_id" name="idrole">

      <div class="form-group">
        <label>Nama Role</label>
        <input type="text" name="nama_role" id="nama_role" placeholder="Masukkan nama role (mis. Admin, Kasir, Manajer)" required>
      </div>

      <button type="submit" class="btn-submit" id="submitBtn">SIMPAN ROLE</button>
    </form>
  </div>

  {{-- 🌸 Table --}}
  <div class="table-box">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama Role</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rows as $r)
        <tr>
          <td>{{ $r->idrole }}</td>
          <td>{{ $r->nama_role }}</td>
          <td>
            <button class="btn btn-update"
              onclick="editRole('{{ $r->idrole }}','{{ $r->nama_role }}')">Edit</button>

            <form action="{{ route('role.delete', $r->idrole) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus role ini?')">
              @csrf
              <button type="submit" class="btn btn-delete">Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="3" style="text-align:center;padding:15px;">Belum ada data role 😢</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<script>
  const btnAdd = document.getElementById('btnAdd');
  const formSection = document.getElementById('form-section');
  const formTitle = document.getElementById('form-title');
  const submitBtn = document.getElementById('submitBtn');
  const roleForm = document.getElementById('roleForm');

  btnAdd.addEventListener('click', () => {
    formSection.classList.toggle('active');
    resetForm();
  });

  function editRole(id, nama_role) {
    formSection.classList.add('active');
    formTitle.textContent = "Form Edit Role";
    submitBtn.textContent = "UPDATE ROLE";
    roleForm.action = `/role/${id}/update`;
    document.getElementById('edit_id').value = id;
    document.getElementById('nama_role').value = nama_role;
  }

  function resetForm() {
    formTitle.textContent = "Form Tambah Role";
    submitBtn.textContent = "SIMPAN ROLE";
    roleForm.action = "{{ route('role.store') }}";
    roleForm.reset();
  }
</script>
@endsection

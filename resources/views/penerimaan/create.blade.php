@extends('layouts.master')
@section('title','Buat Penerimaan Baru')

@section('content')
<style>
  .page-title {
    color: #c67c8f;
    font-weight: 700;
    font-size: 1.8rem;
    margin-bottom: 25px;
  }

  .card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 15px rgba(198,124,143,0.15);
    border: 1px solid #ffd6e3;
    padding: 30px 35px;
    margin-bottom: 25px;
  }

  label {
    display: block;
    font-weight: 600;
    color: #4b2e31;
    margin-bottom: 8px;
    font-size: 15px;
  }

  select, input[type="number"] {
    width: 100%;
    border: 1px solid #f9a8d4;
    border-radius: 10px;
    padding: 8px 12px;
    font-size: 14px;
    background: #fffafc;
    transition: all 0.2s;
  }

  select:focus, input:focus {
    outline: none;
    border-color: #f472b6;
    box-shadow: 0 0 6px rgba(244,114,182,0.4);
  }

  .btn-main {
    background: linear-gradient(90deg, #fcbad3, #ffb6c1);
    color: white;
    font-weight: 600;
    border: none;
    border-radius: 10px;
    padding: 10px 20px;
    box-shadow: 0 4px 10px rgba(198,124,143,0.25);
    cursor: pointer;
    transition: 0.3s ease;
  }

  .btn-main:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(198,124,143,0.35);
  }

  table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    border-radius: 12px;
    overflow: hidden;
    margin-top: 20px;
  }

  thead {
    background: linear-gradient(90deg, #fcbad3, #ffb6c1);
    color: white;
  }

  thead th {
    padding: 10px;
    text-align: center;
    font-weight: 600;
    letter-spacing: 0.3px;
  }

  tbody td {
    padding: 10px;
    border-bottom: 1px solid #ffe4ec;
    color: #4b2e31;
    text-align: center;
  }

  tbody tr:nth-child(even) {
    background: #fff5f8;
  }

  tbody tr:hover {
    background: #ffe9f0;
    transition: 0.2s;
  }

  #barangContainer {
    margin-top: 25px;
  }

  .no-barang {
    text-align: center;
    padding: 15px;
    color: #a17c83;
  }

</style>

<h1 class="page-title text-center">🆕 Buat Batch Penerimaan Baru</h1>

<div class="card">
  <form method="POST" action="{{ route('penerimaan.store') }}">
    @csrf
    <div style="margin-bottom: 20px;">
      <label for="idpengadaan">Pilih Pengadaan:</label>
      <select name="idpengadaan" id="idpengadaan" required>
        <option value="">-- Pilih Pengadaan --</option>
        @foreach($pengadaan as $p)
          <option value="{{ $p->idpengadaan }}">#{{ $p->idpengadaan }} - {{ $p->nama_vendor }}</option>
        @endforeach
      </select>
    </div>

    <button type="button" id="btnLoad" class="btn-main w-full">📦 Muat Barang dari Pengadaan</button>

    <div id="barangContainer" style="display:none;">
      <table class="table">
        <thead>
          <tr>
            <th>Nama Barang</th>
            <th>Satuan</th>
            <th>Dipesan</th>
            <th>Sisa</th>
            <th>Jumlah Diterima</th>
            <th>Harga Diterima</th>
          </tr>
        </thead>
        <tbody id="barangList"></tbody>
      </table>

      <div style="text-align:center;margin-top:20px;">
        <button type="submit" class="btn-main">💾 Simpan Permanen ke Database</button>
      </div>
    </div>
  </form>
</div>

<script>
document.getElementById('btnLoad').addEventListener('click', function() {
  const idp = document.getElementById('idpengadaan').value;
  if (!idp) return alert('Pilih pengadaan terlebih dahulu!');
  
  fetch(`/penerimaan/load-barang/${idp}`)
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById('barangList');
      tbody.innerHTML = '';
      if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="no-barang">Semua barang sudah diterima 💖</td></tr>';
      } else {
        data.forEach(d => {
          tbody.innerHTML += `
            <tr>
              <td>${d.nama_barang}</td>
              <td>${d.nama_satuan}</td>
              <td>${d.jumlah_pesan}</td>
              <td>${d.sisa}</td>
              <td>
                <input type="number" name="jumlah_terima[${d.idbarang}]" min="1" max="${d.sisa}" value="${d.sisa}">
              </td>
              <td>
                <input type="number" name="harga_terima[${d.idbarang}]" value="${d.harga_satuan}">
              </td>
            </tr>`;
        });
      }
      document.getElementById('barangContainer').style.display = 'block';
    })
    .catch(err => alert('Gagal memuat barang: ' + err));
});
</script>
@endsection

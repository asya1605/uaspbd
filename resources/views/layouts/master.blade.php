<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Shashy Beauty')</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
  <style>
    :root {
      --pink-main: #fcbad3;
      --pink-dark: #ff9aa2;
      --rose-gold: #c67c8f;
      --dark-text: #4b2e31;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to bottom right, #fff7fa, #ffeaf1);
      color: var(--dark-text);
      padding-top: 80px; /* biar konten gak ketutup navbar fixed */
      overflow-x: hidden;
    }

    /* 🌸 Navbar fixed */
    .navbar {
      position: fixed;
      top: 0; left: 0; right: 0;
      background: rgba(255,255,255,0.85);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid #ffdce7;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 30px;
      z-index: 999;
      box-shadow: 0 4px 15px rgba(198,124,143,0.25);
    }

    .navbar a {
      color: var(--dark-text);
      font-weight: 600;
      text-decoration: none;
      transition: 0.2s;
    }

    .navbar a:hover {
      color: var(--rose-gold);
    }

    .logout-btn {
      background: linear-gradient(135deg, var(--pink-main), var(--pink-dark));
      color: white;
      font-weight: 600;
      padding: 8px 18px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(252,186,211,0.5);
      transition: 0.3s;
    }

    .logout-btn:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 14px rgba(198,124,143,0.35);
    }

    /* 💄 Dropdown Styling */
    .dropdown {
      position: relative;
    }

    .dropdown-content {
      position: absolute;
      top: 40px;
      left: 0;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(198,124,143,0.2);
      padding: 10px 0;
      min-width: 200px;
      display: none;
      z-index: 1000;
      animation: fadeIn 0.25s ease;
    }

    .dropdown-content a {
      display: block;
      padding: 8px 16px;
      color: var(--dark-text);
      font-weight: 500;
      text-decoration: none;
      transition: 0.2s;
    }

    .dropdown-content a:hover {
      background: rgba(252,186,211,0.25);
      color: var(--rose-gold);
    }

    .dropdown.active .dropdown-content {
      display: block;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-5px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* 🌷 Footer */
    footer {
      background: linear-gradient(90deg, #ffeaf1, #fff0f6);
      color: #6b4e57;
      text-align: center;
      padding: 20px;
      font-size: 0.9rem;
      border-top: 1px solid #ffd6e3;
      box-shadow: 0 -3px 10px rgba(198,124,143,0.15);
      margin-top: 60px;
    }
  </style>
</head>

<body>

  {{-- 💄 Navbar --}}
  <nav class="navbar">
    {{-- ✨ Tambahkan link ke dashboard --}}
    <a href="{{ route('dashboard') }}" class="font-bold text-[18px] hover:text-rose-500 transition">
      Dashboard Admin
    </a>

    <div class="flex items-center gap-6">

      {{-- 🗂️ Data Master --}}
      <div class="dropdown">
        <a href="#" class="dropdown-toggle">Data Master ▾</a>
        <div class="dropdown-content">
          <a href="{{ route('barang.index') }}">📦 Barang</a>
          <a href="{{ route('vendor.index') }}">🏪 Vendor</a>
          <a href="{{ route('satuan.index') }}">📏 Satuan</a>
          <a href="{{ route('users.index') }}">👤 User</a>
          <a href="{{ route('margin.index') }}">💹 Margin</a>
          <a href="{{ route('role.index') }}">🧩 Role</a>
        </div>
      </div>

      {{-- 💸 Transaksi --}}
      <div class="dropdown">
        <a href="#" class="dropdown-toggle">Transaksi ▾</a>
        <div class="dropdown-content">
          <a href="{{ route('pengadaan.index') }}">📥 Pengadaan</a>
          <a href="{{ route('penerimaan.index') }}">📦 Penerimaan</a>
          <a href="{{ route('penjualan.index') }}">💄 Penjualan</a>
          <a href="{{ route('kartustok.index') }}">📊 Kartu Stok</a>
        </div>
      </div>

      <a href="{{ route('logout') }}" class="logout-btn">LOG OUT</a>
    </div>
  </nav>

  {{-- 💕 Konten halaman --}}
  <main class="mt-10 px-4">
    @yield('content')
  </main>

  {{-- 🌸 Footer --}}
  <footer>
    © 2025 <b>Shashy Beauty</b> 💄 | Designed with 💕 by <b>Shashy Team</b>
  </footer>

  <script>
    // 🌸 Dropdown toggle fix (klik, bukan hover)
    document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
      toggle.addEventListener('click', function (e) {
        e.preventDefault();
        const parent = this.parentElement;

        // Tutup semua dropdown lain dulu
        document.querySelectorAll('.dropdown').forEach(d => {
          if (d !== parent) d.classList.remove('active');
        });

        // Toggle dropdown yang diklik
        parent.classList.toggle('active');
      });
    });

    // Tutup dropdown kalau klik di luar area
    document.addEventListener('click', function (e) {
      const dropdowns = document.querySelectorAll('.dropdown');
      dropdowns.forEach(d => {
        if (!d.contains(e.target)) d.classList.remove('active');
      });
    });
  </script>
@if(session('ok'))
<script>
document.addEventListener('DOMContentLoaded', () => {
  let toast = document.createElement('div');
  toast.textContent = "{{ session('ok') }}";
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
  toast.style.transition = 'all 0.3s';
  toast.style.opacity = '0';
  document.body.appendChild(toast);
  setTimeout(() => toast.style.opacity = '1', 100);
  setTimeout(() => { toast.style.opacity = '0'; setTimeout(()=>toast.remove(),300); }, 3500);
});
</script>
@endif

@if(session('ok'))
<script>
document.addEventListener('DOMContentLoaded', () => {
  let toast = document.createElement('div');
  toast.textContent = "{{ session('ok') }}";
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
});
</script>
@endif

</body>
</html>

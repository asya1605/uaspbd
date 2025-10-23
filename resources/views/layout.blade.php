<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','CRUD Proyek PBD')</title>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app-ui.css') }}">
  @stack('styles')
</head>
<body>

  {{-- NAVBAR GLOBAL --}}
    @php
         $me = session('user');
        $role = strtolower($me['role'] ?? '');
        $username = $me['username'] ?? ($role ?: 'User');

        // buat penanda menu aktif
        $path = request()->path();
        $is = fn($p) => str_starts_with($path, trim($p,'/')) ? 'active' : '';
    @endphp

  <header class="nav">
    <div class="nav-inner">
      <a class="brand" href="/dashboard">⚙️ CRUD Proyek PBD</a>

      <nav class="links">
        <a class="{{ $is('dashboard') }}" href="/dashboard">Dashboard</a>

        {{-- ===== MENU MASTER: hanya untuk super_admin ===== --}}
        @if($role === 'super_admin')
          <a class="{{ $is('satuan') }}" href="/satuan">Satuan</a>
          <a class="{{ $is('vendor') }}" href="/vendor">Vendor</a>
          <a class="{{ $is('barang') }}" href="/barang">Barang</a>
          <a class="{{ $is('role') }}" href="/role">Role</a>
          <a class="{{ $is('users') }}" href="/users">Users</a>
        @endif

        {{-- ===== TRANSAKSI PENJUALAN: admin & super_admin boleh ===== --}}
        @if(in_array($role, ['admin','super_admin']))
          <a class="{{ $is('penjualan') }}" href="/penjualan">Penjualan</a>
        @endif

        <div class="sep"></div>

        {{-- Laporan/View (boleh untuk admin & super_admin; sesuaikan kalau perlu) --}}
        <div class="dropdown">
          <button class="drop-btn {{ in_array($path, ['view-pengadaan','view-stok','view-penjualan-harian']) ? 'active' : '' }}">
            Laporan ▾
          </button>
          <div class="drop-menu">
            <a href="/view-pengadaan">View Pengadaan</a>
            <a href="/view-stok">View Stok</a>
            <a href="/view-penjualan-harian">Penjualan Harian</a>
          </div>
        </div>

        <div class="spacer"></div>

        <span class="welcome">
            Welcome, <b>{{ $username }}</b>
            @if($role) <small style="opacity:.7">({{ $role }})</small> @endif
        </span>
        <form method="POST" action="/logout" class="logout-form">@csrf
          <button type="submit" class="btn-logout">Logout</button>
        </form>
      </nav>
    </div>
  </header>

  {{-- KONTEN HALAMAN --}}
  <main class="main">
    @yield('content')
  </main>

  {{-- FOOTER SIMPLE --}}
  <footer class="footer">
    <small>© {{ date('Y') }} CRUD Proyek PBD.</small>
  </footer>

  @stack('scripts')
</body>
</html>

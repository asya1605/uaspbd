<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') | Sistem Inventori</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
  <style>
    body { background: #f9fafb; color: #1f2937; }
    .page-title { font-size: 24px; font-weight: 700; margin-bottom: 1rem; color: #1e3a8a; }
    .card {
      background: white; border-radius: 12px; padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 24px;
    }
    .btn {
      background-color: #1e40af; color: white; border-radius: 8px;
      padding: 8px 14px; font-weight: 600; transition: 0.2s;
    }
    .btn:hover { background-color: #1d4ed8; }
    .alert-ok {
      background: #d1fae5; color: #065f46;
      border-left: 5px solid #10b981; padding: 10px 16px;
      border-radius: 6px; margin-bottom: 12px;
    }
    .alert-err {
      background: #fee2e2; color: #991b1b;
      border-left: 5px solid #ef4444; padding: 10px 16px;
      border-radius: 6px; margin-bottom: 12px;
    }
    nav a.active { text-decoration: underline; font-weight: 700; }
  </style>
</head>

<body>
  {{-- 🔹 Navbar --}}
  <nav class="bg-blue-900 text-white py-3 px-6 flex items-center justify-between shadow-md">
    <div class="flex items-center gap-6 font-semibold text-sm">
      <a href="{{ route('dashboard') }}" class="hover:text-yellow-300">🏠 Dashboard</a>
      <a href="{{ route('role.index') }}" class="hover:text-yellow-300">Role</a>
      <a href="{{ route('users.index') }}" class="hover:text-yellow-300">User</a>
      <a href="{{ route('vendor.index') }}" class="hover:text-yellow-300">Vendor</a>
      <a href="{{ route('satuan.index') }}" class="hover:text-yellow-300">Satuan</a>
      <a href="{{ route('barang.index') }}" class="hover:text-yellow-300">Barang</a>
      <a href="{{ route('pengadaan.index') }}" class="hover:text-yellow-300">Pengadaan</a>
      <a href="{{ route('penjualan.index') }}" class="hover:text-yellow-300">Penjualan</a>
      <a href="{{ route('stok.update') }}" class="hover:text-yellow-300">Stok</a>
    </div>

    {{-- Tombol logout --}}
    <form method="GET" action="{{ route('logout') }}">
      <button class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-sm font-semibold">🚪 Logout</button>
    </form>
  </nav>

  {{-- 🔹 Main Content --}}
  <main class="max-w-6xl mx-auto py-10 px-6">
    @yield('content')
  </main>

  {{-- 🔹 Footer --}}
  <footer class="text-center text-gray-500 text-sm py-6 border-t mt-10">
    &copy; {{ date('Y') }} Sistem Inventori Barang — Semua Hak Dilindungi
  </footer>
</body>
</html>

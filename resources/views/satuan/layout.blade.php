<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Aplikasi PBD')</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
    body {font-family: Inter, sans-serif; background:#f9fafb; margin:0; padding:0;}
    nav {background:#002080; padding:10px 20px; display:flex; gap:14px; align-items:center;}
    nav a, nav form button {
      color:white; text-decoration:none; font-weight:500; background:none; border:none;
      cursor:pointer; transition:.2s;
    }
    nav a:hover, nav form button:hover {color:#ffd700;}
    .container {max-width:1100px; margin:30px auto; background:white; padding:24px; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.1);}
    .page-title {font-size:22px; font-weight:700; margin-bottom:20px; color:#111827;}
    .btn {
      background:#002080; color:white; border:none; border-radius:8px;
      padding:9px 16px; cursor:pointer; transition:.2s;
    }
    .btn:hover {background:#3344cc;}
    .card {background:white; border-radius:8px; padding:16px;}
  </style>
</head>
<body>
  <nav>
    <a href="/dashboard">🏠 Dashboard</a>
    <a href="/satuan">⚙️ Satuan</a>
    <a href="/vendor">🏭 Vendor</a>
    <a href="/role">🧩 Role</a>
    <a href="/users">👤 Users</a>
    <a href="/barang">📦 Barang</a>
    <a href="/penjualan">💰 Penjualan</a>
    <form method="post" action="/logout" style="margin-left:auto">@csrf
      <button>🚪 Logout</button>
    </form>
  </nav>

  <div class="container">
    @yield('content')
  </div>
</body>
</html>

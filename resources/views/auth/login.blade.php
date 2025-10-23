<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login | Proyek PBD</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {font-family:Inter,sans-serif; background:#eef2ff; display:flex; align-items:center; justify-content:center; height:100vh; margin:0;}
    .card {background:white; padding:36px; border-radius:14px; box-shadow:0 4px 12px rgba(0,0,0,0.1); text-align:center; width:340px;}
    .title {font-size:20px; font-weight:700; color:#111827;}
    .subtitle {font-size:14px; color:#6b7280; margin-bottom:18px;}
    .input {display:flex; align-items:center; border:1px solid #d1d5db; border-radius:8px; margin-top:8px; padding:8px;}
    input {border:none; outline:none; flex:1; font-size:14px;}
    .btn {background:#002080; color:white; padding:10px; width:100%; border:none; border-radius:8px; margin-top:16px; cursor:pointer;}
    .alert {background:#fee2e2; color:#b91c1c; border:1px solid #fecaca; padding:8px; border-radius:8px; margin-bottom:10px;}
  </style>
</head>
<body>
  <main class="card">
    <h1 class="title">Login Proyek PBD</h1>
    <p class="subtitle">Masuk ke Sistem CRUD</p>

    @if ($errors->any())
      <div class="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="/login">
      @csrf
      <label>
        <span>Username</span>
        <div class="input">
          👤 <input name="username" value="{{ old('username') }}" required autofocus>
        </div>
      </label>

      <label>
        <span>Password</span>
        <div class="input">
          🔒 <input type="password" name="password" required>
        </div>
      </label>

      <button class="btn" type="submit">Masuk</button>
    </form>
  </main>
</body>
</html>

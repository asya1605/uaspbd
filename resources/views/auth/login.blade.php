<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login | Shashy Beauty</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --pink-main: #ffb6c1;
      --rose-gold: #d58b9b;
      --light-bg: #fff6f8;
      --text-dark: #4a2c2a;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, var(--light-bg), #ffe4ec);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .card {
      background: white;
      padding: 40px 36px;
      border-radius: 20px;
      box-shadow: 0 8px 25px rgba(213, 139, 155, 0.25);
      text-align: center;
      width: 360px;
      border: 2px solid #ffd6e3;
    }

    .title {
      font-size: 22px;
      font-weight: 700;
      color: var(--rose-gold);
      margin-bottom: 6px;
    }

    .subtitle {
      font-size: 14px;
      color: #9f6c72;
      margin-bottom: 22px;
    }

    label span {
      font-weight: 600;
      color: var(--text-dark);
      display: block;
      text-align: left;
      margin-top: 10px;
      font-size: 13px;
    }

    .input {
      display: flex;
      align-items: center;
      border: 1.5px solid #f3b8c0;
      border-radius: 10px;
      margin-top: 6px;
      padding: 10px;
      transition: 0.3s;
    }

    .input:focus-within {
      border-color: var(--rose-gold);
      box-shadow: 0 0 8px rgba(213,139,155,0.3);
    }

    input {
      border: none;
      outline: none;
      flex: 1;
      font-size: 14px;
      color: #333;
      background: transparent;
    }

    .btn {
      background: linear-gradient(135deg, var(--pink-main), var(--rose-gold));
      color: white;
      padding: 10px;
      width: 100%;
      border: none;
      border-radius: 10px;
      margin-top: 20px;
      font-weight: 600;
      cursor: pointer;
      letter-spacing: 0.3px;
      transition: 0.3s;
    }

    .btn:hover {
      background: var(--rose-gold);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(213,139,155,0.4);
    }

    .alert {
      background: #ffe6ea;
      color: #b91c1c;
      border: 1px solid #ffc8d0;
      padding: 8px;
      border-radius: 8px;
      margin-bottom: 12px;
      font-size: 13px;
    }
  </style>
</head>
<body>
  <main class="card">
    <h1 class="title">Login Shashy Beauty</h1>
    <p class="subtitle">Masuk untuk kelola koleksi kecantikanmu ✨</p>

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

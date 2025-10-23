<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kedai Toji — Welcome</title>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>

  <!-- Navbar -->
  <header class="nav">
    <div class="nav-inner">
      <div class="brand">Kedai<span>Toji</span></div>
      <nav class="links">
        <a href="/">Home</a>
        <a href="#products">Products</a>
        <a class="btn" href="/login">Login</a>
      </nav>
    </div>
  </header>

  <!-- Hero -->
  <section class="hero">
    <img class="hero-bg" src="{{ asset('images/hero.jpg') }}" alt="Background">
    <div class="overlay"></div>

    <div class="hero-content">
      <h1>Welcome</h1>
      <p>Your elegant journey begins here.</p>
      <a href="#products" class="cta">Explore Our Collection</a>
    </div>
  </section>

  <section id="products" class="products">
    <h2>Featured Products</h2>
    <p>Section contoh—nanti bisa kamu ganti.</p>
  </section>

  <footer class="footer">
    © 2025 Kedai Toji Inc. All rights reserved.
  </footer>
</body>
</html>

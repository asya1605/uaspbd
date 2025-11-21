<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Shashy Beauty — Glow With Confidence</title>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --pink-main: #ffb6c1;
      --pink-light: #fff0f5;
      --rose-gold: #d58b9b;
      --dark-text: #4a2c2a;
      --white: #fff;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--pink-light);
      margin: 0;
      color: var(--dark-text);
      scroll-behavior: smooth;
    }

    /* 🌸 Navbar */
    .nav {
      background: var(--white);
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      position: sticky;
      top: 0;
      z-index: 10;
    }
    .nav-inner {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 40px;
    }
    .brand {
      font-weight: 700;
      font-size: 1.5rem;
      color: var(--rose-gold);
      letter-spacing: 0.5px;
    }
    .brand span {
      color: var(--pink-main);
    }
    .links a {
      text-decoration: none;
      color: var(--dark-text);
      font-weight: 600;
      margin: 0 15px;
      transition: 0.3s;
    }
    .links a:hover {
      color: var(--rose-gold);
    }
    .links .btn {
      background: linear-gradient(135deg, var(--pink-main), var(--rose-gold));
      color: var(--white);
      padding: 8px 16px;
      border-radius: 30px;
      transition: 0.3s;
    }
    .links .btn:hover {
      background: var(--rose-gold);
      color: var(--white);
    }

    /* 💋 Hero */
    .hero {
      position: relative;
      text-align: center;
      color: var(--white);
      overflow: hidden;
    }
    .hero-bg {
      width: 100%;
      height: 90vh;
      object-fit: cover;
      filter: brightness(75%);
    }
    .overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to bottom right, rgba(255,182,193,0.5), rgba(213,139,155,0.5));
    }
    .hero-content {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      max-width: 700px;
      text-align: center;
    }
    .hero-content h1 {
      font-size: 3rem;
      font-weight: 700;
      color: var(--white);
      text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }
    .hero-content p {
      font-size: 1.2rem;
      margin-top: 10px;
    }
    .cta {
      display: inline-block;
      background: linear-gradient(135deg, var(--pink-main), var(--rose-gold));
      color: var(--white);
      padding: 12px 25px;
      border-radius: 30px;
      text-decoration: none;
      font-weight: 600;
      margin-top: 20px;
      transition: 0.3s;
    }
    .cta:hover {
      background: var(--rose-gold);
    }

    /* 🧴 Produk */
    .products {
      padding: 80px 20px;
      text-align: center;
      background-color: var(--white);
    }
    .products h2 {
      color: var(--rose-gold);
      font-size: 2rem;
      margin-bottom: 10px;
    }
    .products p {
      color: #6b4b4b;
      margin-bottom: 40px;
    }

    /* 💎 Grid produk */
    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
      margin-top: 40px;
      padding: 0 40px;
    }

    .product-card {
      background: var(--pink-light);
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(213,139,155,0.15);
      padding: 20px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(213,139,155,0.25);
    }
    .product-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 15px;
    }
    .product-card h3 {
      color: var(--rose-gold);
      font-weight: 700;
      margin: 8px 0 5px;
    }
    .product-card p {
      color: #6b4b4b;
      font-size: 0.9rem;
      line-height: 1.4;
    }

    /* 👠 Footer */
    .footer {
      background: var(--pink-main);
      text-align: center;
      padding: 15px;
      color: var(--white);
      font-size: 0.9rem;
      letter-spacing: 0.3px;
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <header class="nav">
    <div class="nav-inner">
      <div class="brand">Shashy<span>Beauty</span></div>
      <nav class="links">
        <a href="/">Home</a>
        <a class="btn" href="/login">Login</a>
      </nav>
    </div>
  </header>

  <!-- Hero -->
  <section class="hero">
    <img class="hero-bg" src="{{ asset('images/hero.jpg') }}" alt="Background">
    <div class="overlay"></div>
    <div class="hero-content">
      <h1>Find Your Glow ✨</h1>
      <p>Rangkaian skincare & make-up untuk tampil percaya diri setiap hari.</p>
      <a href="#products" class="cta">Lihat Koleksi Kami</a>
    </div>
  </section>

  <!-- Produk -->
  <section id="products" class="products">
    <h2>Produk Unggulan</h2>
    <p>Temukan kecantikan alami dalam setiap sentuhan produk kami.</p>

    <div class="product-grid">
      <div class="product-card">
        <img src="{{ asset('images/produk1.jpg') }}" alt="Tinted Lip Balm Avery">
        <h3>Tinted Lip Balm Avery</h3>
        <p>Bibir lembap dan lembut alami dengan sentuhan warna elegan Avery.</p>
      </div>

      <div class="product-card">
        <img src="{{ asset('images/produk2.jpg') }}" alt="Flawless Ivy">
        <h3>Flawless Ivy</h3>
        <p>Foundation ringan yang memberikan hasil akhir halus dan bercahaya.</p>
      </div>

      <div class="product-card">
        <img src="{{ asset('images/produk3.jpg') }}" alt="Serum Bright Essence">
        <h3>Serum Bright Essence</h3>
        <p>Serum pencerah dengan kandungan aktif untuk kulit tampak segar dan glowing.</p>
      </div>

      <div class="product-card">
        <img src="{{ asset('images/produk4.jpg') }}" alt="Sunscreen Matte Shield SPF50">
        <h3>Sunscreen Matte Shield SPF50</h3>
        <p>Perlindungan maksimal dari sinar UV dengan hasil akhir matte tanpa lengket.</p>
      </div>

      <div class="product-card">
        <img src="{{ asset('images/produk5.jpg') }}" alt="Toner Hydrating Mist">
        <h3>Toner Hydrating Mist</h3>
        <p>Menyegarkan dan menjaga kelembapan alami kulit setiap saat.</p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    © 2025 Shashy Beauty 💄 | Glow With Confidence ✨
  </footer>

</body>
</html>

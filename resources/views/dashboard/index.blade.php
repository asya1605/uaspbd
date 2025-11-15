@extends('layouts.master')

@section('title', 'Dashboard Shashy Beauty')

@section('content')
  {{-- 🌸 Hero Section --}}
  <div class="hero relative w-full h-80 rounded-b-[40px] overflow-hidden shadow-lg">
    <img src="https://i.pinimg.com/1200x/4f/b0/47/4fb0473c9f3afe4bf015f5ca919369f5.jpg"
         alt="Shashy Beauty Banner"
         class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-b from-pink-200/40 to-pink-100/90"></div>

    <div class="relative z-10 text-center text-white top-1/2 transform -translate-y-1/2">
      <h1 class="text-3xl font-extrabold drop-shadow-md">Welcome to Shashy Beauty 💄</h1>
      <p class="mt-2 text-white/90 font-medium">Elegance, Glow, and Perfect Management ✨</p>
    </div>
  </div>

  {{-- 💕 Data Master Section --}}
  <section class="card-section mt-16 bg-white/90 backdrop-blur-sm shadow-xl border border-pink-100 rounded-2xl max-w-6xl mx-auto text-center p-10">
    <h3 class="text-xl font-semibold text-rose-600 mb-1">
      Hai, <span class="text-rose-400">{{ $username }}</span> <span class="glow">🌸</span>
    </h3>
    <p class="text-gray-600 mb-6">Berikut ringkasan data master kamu hari ini:</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
      <div class="mini-card">
        <h2>{{ $counts['role'] }}</h2>
        <h4>Role</h4>
        <p>Total role terdaftar.</p>
      </div>
      <div class="mini-card">
        <h2>{{ $counts['user'] }}</h2>
        <h4>User</h4>
        <p>Total pengguna sistem.</p>
      </div>
      <div class="mini-card">
        <h2>{{ $counts['vendor'] }}</h2>
        <h4>Vendor</h4>
        <p>Total mitra vendor aktif.</p>
      </div>
      <div class="mini-card">
        <h2>{{ $counts['satuan'] }}</h2>
        <h4>Satuan</h4>
        <p>Total satuan stok tercatat.</p>
      </div>
      <div class="mini-card">
        <h2>{{ $counts['barang'] }}</h2>
        <h4>Barang</h4>
        <p>Total barang tersedia.</p>
      </div>
    </div>
  </section>

  {{-- 📊 Statistik Transaksi --}}
  <section class="card-section mt-12 bg-white/90 backdrop-blur-sm shadow-xl border border-pink-100 rounded-2xl max-w-6xl mx-auto text-center p-10">
    <h3 class="text-xl font-semibold text-rose-600 mb-1">📊 Statistik Transaksi</h3>
    <p class="text-gray-600 mb-6">Data aktivitas pengadaan dan penjualan terkini:</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-6 gap-6">
      <div class="mini-card">
        <h2>0+</h2>
        <h4>Pengadaan Pending</h4>
        <p>Total pengadaan berstatus pending.</p>
      </div>
      <div class="mini-card">
        <h2>0+</h2>
        <h4>Pengadaan Selesai</h4>
        <p>Total pengadaan selesai diproses.</p>
      </div>
      <div class="mini-card">
        <h2>0+</h2>
        <h4>Penerimaan Diretur</h4>
        <p>Total penerimaan yang dikembalikan.</p>
      </div>
      <div class="mini-card">
        <h2>0+</h2>
        <h4>Pengadaan Dibatalkan</h4>
        <p>Total pengadaan yang dicancel.</p>
      </div>
      <div class="mini-card">
        <h2>0+</h2>
        <h4>Pengadaan Dalam Proses</h4>
        <p>Total pengadaan yang masih berjalan.</p>
      </div>
      <div class="mini-card">
        <h2>0+</h2>
        <h4>Penjualan Terjual</h4>
        <p>Total penjualan berhasil.</p>
      </div>
    </div>
  </section>

  <style>
    .mini-card {
      background: linear-gradient(to bottom, #ffffff, #fff8fa);
      border-radius: 16px;
      padding: 25px 20px;
      box-shadow: 0 6px 18px rgba(198,124,143,0.12);
      transition: 0.3s ease;
      border: 1px solid #ffe0eb;
    }

    .mini-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(198,124,143,0.25);
    }

    .mini-card h2 {
      font-size: 1.8rem;
      color: #ff9aa2;
      font-weight: 700;
      margin-bottom: 6px;
    }

    .mini-card h4 {
      font-size: 1rem;
      font-weight: 700;
      color: #4b2e31;
    }

    .mini-card p {
      color: #7a5c63;
      font-size: 0.85rem;
      margin-top: 5px;
    }

    .glow {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { filter: drop-shadow(0 0 3px #ffb6c1); }
      50% { filter: drop-shadow(0 0 8px #ff91a4); }
      100% { filter: drop-shadow(0 0 3px #ffb6c1); }
    }
  </style>
@endsection

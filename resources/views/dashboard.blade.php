@extends('layout')
@section('title','Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<main class="container">
  <h1 class="page-title">Sistem CRUD Proyek PBD</h1>

  {{-- badges ringkas --}}
  <div class="badges">
    <div class="badge b-role">
      <span class="label">Role</span>
      <span class="val">{{ $counts['role'] }}</span>
    </div>
    <div class="badge b-user">
      <span class="label">User</span>
      <span class="val">{{ $counts['user'] }}</span>
    </div>
    <div class="badge b-vendor">
      <span class="label">Vendor</span>
      <span class="val">{{ $counts['vendor'] }}</span>
    </div>
    <div class="badge b-satuan">
      <span class="label">Satuan</span>
      <span class="val">{{ $counts['satuan'] }}</span>
    </div>
    <div class="badge b-barang">
      <span class="label">Barang</span>
      <span class="val">{{ $counts['barang'] }}</span>
    </div>
  </div>

  {{-- grid kartu menu --}}
  <div class="grid">
    <div class="card">
      <div class="icon">🛡️</div>
      <h3>Role</h3>
      <a href="/role" class="btn">Kelola Role</a>
    </div>

    <div class="card">
      <div class="icon">👥</div>
      <h3>User</h3>
      <a href="/users" class="btn">Kelola User</a>
    </div>

    <div class="card">
      <div class="icon">🏢</div>
      <h3>Vendor</h3>
      <a href="/vendor" class="btn">Kelola Vendor</a>
    </div>

    <div class="card">
      <div class="icon">📏</div>
      <h3>Satuan</h3>
      <a href="/satuan" class="btn">Kelola Satuan</a>
    </div>

    <div class="card">
      <div class="icon">🧰</div>
      <h3>Barang</h3>
      <a href="/barang" class="btn">Kelola Barang</a>
    </div>
  </div>
</main>
@endsection

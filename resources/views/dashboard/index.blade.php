@extends('layouts.master')
@section('title','Dashboard')

@section('content')
<h1 class="page-title">Dashboard Proyek PBD</h1>

<div class="card">
  <h3>Selamat datang, {{ $username }}</h3>
  <p>Jumlah data master saat ini:</p>

  <ul>
    <li>Role : <b>{{ $counts['role'] }}</b></li>
    <li>User : <b>{{ $counts['user'] }}</b></li>
    <li>Vendor : <b>{{ $counts['vendor'] }}</b></li>
    <li>Satuan : <b>{{ $counts['satuan'] }}</b></li>
    <li>Barang : <b>{{ $counts['barang'] }}</b></li>
  </ul>
</div>
@endsection

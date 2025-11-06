@extends('layouts.master')
@section('title', 'Halaman Tidak Ditemukan')

@section('content')
<section style="text-align:center; padding:80px 20px;">
    <h1 style="font-size:80px; font-weight:700; color:#2563eb;">404</h1>
    <h2 style="font-size:22px; margin-top:10px; color:#374151;">
        Halaman yang kamu cari tidak ditemukan 😢
    </h2>
    <p style="margin-top:15px; color:#6b7280;">
        Mungkin alamat URL salah, atau halaman ini sudah dipindahkan.
    </p>
    <a href="{{ route('dashboard') }}" 
       style="display:inline-block; margin-top:25px; padding:10px 20px; 
              background-color:#2563eb; color:white; border-radius:8px; text-decoration:none;">
        ⬅️ Kembali ke Dashboard
    </a>
</section>
@endsection

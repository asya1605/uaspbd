<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Shashy Beauty')</title>

    {{-- Tailwind --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">

    <style>
        :root {
            --pink-main: #fcbad3;
            --pink-dark: #ff9aa2;
            --rose-gold: #c67c8f;
            --dark-text: #4b2e31;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom right, #fff7fa, #ffeaf1);
            color: var(--dark-text);
            padding-top: 80px;
            overflow-x: hidden;
        }

        /* 🌸 NAVBAR */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #ffdce7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 32px;
            z-index: 999;
            box-shadow: 0 4px 15px rgba(198,124,143,0.25);
        }

        /* 🌸 Dropdown */
        .dropdown { position: relative; }

        .dropdown-content {
            position: absolute;
            top: 40px;
            left: 0;
            min-width: 200px;
            background: white;
            border-radius: 10px;
            padding: 8px 0;
            box-shadow: 0 4px 15px rgba(198,124,143,0.18);
            display: none;
            animation: fade .25s ease;
            z-index: 9999;
        }

        .dropdown-content a {
            display: block;
            padding: 8px 16px;
            font-weight: 500;
            color: var(--dark-text);
        }

        .dropdown-content a:hover {
            background: rgba(252,186,211,0.25);
            color: var(--rose-gold);
        }

        .dropdown.active .dropdown-content {
            display: block;
        }

        @keyframes fade {
            from { opacity: 0; transform: translateY(-5px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* 🌸 Logout button */
        .logout-btn {
            background: linear-gradient(135deg, var(--pink-main), var(--pink-dark));
            padding: 8px 18px;
            border-radius: 10px;
            font-weight: 600;
            color: white;
            box-shadow: 0 4px 12px rgba(252,186,211,0.55);
            transition: .25s;
        }

        .logout-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 18px rgba(198,124,143,0.35);
        }

        /* 🌼 Footer */
        footer {
            background: linear-gradient(90deg, #ffeaf1, #fff0f6);
            color: #6b4e57;
            text-align: center;
            padding: 20px;
            font-size: .9rem;
            border-top: 1px solid #ffd6e3;
            margin-top: 60px;
            box-shadow: 0 -3px 10px rgba(198,124,143,0.15);
        }
    </style>
</head>

<body>

    {{-- ❤️ NAVBAR --}}
    <nav class="navbar">

        <a href="{{ route('dashboard') }}"
           class="font-bold text-[18px] hover:text-rose-500">
            Dashboard Admin
        </a>

        <div class="flex items-center gap-6">

            {{-- DATA MASTER --}}
            <div class="dropdown">
                <a href="#" class="dropdown-toggle">Data Master ▾</a>
                <div class="dropdown-content">
                    <a href="{{ route('barang.index') }}">📦 Barang</a>
                    <a href="{{ route('vendor.index') }}">🏪 Vendor</a>
                    <a href="{{ route('satuan.index') }}">📏 Satuan</a>
                    <a href="{{ route('users.index') }}">👤 User</a>
                    <a href="{{ route('margin.index') }}">💹 Margin</a>
                    <a href="{{ route('role.index') }}">🧩 Role</a>
                </div>
            </div>

            {{-- TRANSAKSI --}}
            <div class="dropdown">
                <a href="#" class="dropdown-toggle">Transaksi ▾</a>
                <div class="dropdown-content">
                    <a href="{{ route('pengadaan.index') }}">📥 Pengadaan</a>
                    <a href="{{ route('penerimaan.index') }}">📦 Penerimaan</a>
                    <a href="{{ route('penjualan.index') }}">💄 Penjualan</a>
                    <a href="{{ route('kartustok.index') }}">📊 Kartu Stok</a>
                </div>
            </div>

            <a class="logout-btn" href="{{ route('logout') }}">LOG OUT</a>
        </div>
    </nav>

    {{-- ❤️ CONTENT --}}
    <main class="mt-10 px-4">
        @yield('content')
    </main>

    {{-- ❤️ FOOTER --}}
    <footer>
        © 2025 <b>Shashy Beauty</b> 💄 | Designed with 💕 by <b>Shashy Team</b>
    </footer>

    {{-- 🌸 Dropdown Script --}}
    <script>
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();

                const parent = this.parentElement;

                document.querySelectorAll('.dropdown').forEach(d => {
                    if (d !== parent) d.classList.remove('active');
                });

                parent.classList.toggle('active');
            });
        });

        document.addEventListener('click', function (e) {
            document.querySelectorAll('.dropdown').forEach(d => {
                if (!d.contains(e.target)) d.classList.remove('active');
            });
        });
    </script>

    {{-- 🌸 Toast --}}
    @if(session('ok'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let toast = document.createElement('div');

            toast.textContent = "{{ session('ok') }}";
            toast.style.position = 'fixed';
            toast.style.bottom = '30px';
            toast.style.right = '30px';
            toast.style.background = '#ffb6c1';
            toast.style.padding = '12px 20px';
            toast.style.borderRadius = '10px';
            toast.style.color = '#4b2e31';
            toast.style.boxShadow = '0 4px 12px rgba(198,124,143,0.25)';
            toast.style.fontWeight = '600';
            toast.style.opacity = '0';
            toast.style.transition = '.3s';
            toast.style.zIndex = '99999';

            document.body.appendChild(toast);
            setTimeout(() => toast.style.opacity = '1', 120);
            setTimeout(() => toast.style.opacity = '0', 2500);
            setTimeout(() => toast.remove(), 3000);
        });
    </script>
    @endif

</body>
</html>

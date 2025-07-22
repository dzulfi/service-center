<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

        {{-- Select2 --}}
        <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* CSS dari _sidebar.blade.php dipindahkan ke sini */
            .sidebar {
                width: 250px;
                background-color: #2c3e50; /* Warna gelap untuk sidebar */
                color: white;
                padding: 20px 0;
                height: 100vh; /* Tinggi penuh viewport */
                position: fixed; /* Tetap di tempat saat scroll */
                top: 0;
                left: 0;
                box-shadow: 2px 0 5px rgba(0,0,0,0.2);
                display: flex;
                flex-direction: column;
                z-index: 10; /* Pastikan sidebar di atas konten lain jika ada */
            }
            .sidebar-header {
                text-align: center;
                margin-bottom: 30px;
                padding: 0 20px;
            }
            .sidebar-header h2 {
                color: #ecf0f1;
                font-size: 1.8em;
                margin: 0;
                padding: 10px 0;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            .sidebar-menu ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            .sidebar-menu ul li {
                margin-bottom: 5px;
            }
            .sidebar-menu ul li a {
                display: block;
                color: #ecf0f1;
                text-decoration: none;
                padding: 12px 20px;
                transition: background-color 0.3s ease, color 0.3s ease;
                font-size: 1.1em;
                border-left: 5px solid transparent;
            }
            .sidebar-menu ul li a:hover {
                background-color: #34495e;
                border-left-color: #3498db;
            }
            .sidebar-menu ul li a.active {
                background-color: #3498db;
                font-weight: bold;
                border-left-color: #ecf0f1;
            }

            /* Main content wrapper to push content next to sidebar */
            .page-wrapper {
                margin-left: 250px; /* Lebar sidebar */
                flex-grow: 1; /* Mengisi sisa ruang */
                min-height: 100vh; /* Pastikan tinggi minimal */
                display: flex;
                flex-direction: column;
            }

            /* Overrides some Breeze defaults to accommodate sidebar */
            .min-h-screen {
                min-height: 0 !important; /* Reset default min-height */
            }
            .font-sans.antialiased {
                display: flex; /* Mengaktifkan flexbox untuk layout utama */
                flex-direction: row; /* Sidebar di kiri, konten di kanan */
            }
            .max-w-7xl { /* Sesuaikan jika perlu */
                max-width: none !important;
            }
            .py-12 { /* Sesuaikan padding agar tidak terlalu mepet */
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        @include('layouts._sidebar') <div class="page-wrapper"> @include('layouts.navigation') @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main>
                @yield('content') 
            </main>
        </div>

        {{-- Select2 --}}
        <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('js/select2.min.js') }}"></script>

        <script>
           jQuery(document).ready(function($) {
                // Customer
                $('#customer_id').select2({
                    placeholder: "-- Pilih Mitra Bisnis --",
                    language: "id"
                });

                // item type
                $('#item_type_id').select2({
                    tags: true
                });

                // sparepart (out RMA)
                $('#sparepart_id').select2({
                    placeholder: "-- Pilih Sparepart --",
                    language: "id"
                });

                // Role user
                $('#role_id').select2({
                    placeholder: "-- Pilih Role --",
                    language: "id"
                });

                $('#branch_office_id').select2({
                    placeholder: "-- Pilih Kantor Cabang --",
                    language: "id"
                });
            })
        </script>
    </body>
</html>
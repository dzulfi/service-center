<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Servis - @yield('title', 'Beranda')</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
            color: #333;
            display: flex; /* Menggunakan flexbox untuk layout */
        }
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

        .main-wrapper {
            margin-left: 250px; /* Sesuaikan dengan lebar sidebar */
            flex-grow: 1; /* Agar konten mengisi sisa ruang */
            padding: 20px;
        }
    </style>
    </head>
<body>
    @include('layouts._sidebar') <div class="main-wrapper">
        @yield('content') </div>

    </body>
</html>
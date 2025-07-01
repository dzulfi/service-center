<style>
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
        border-left: 5px solid transparent; /* Garis indikator aktif */
    }
    .sidebar-menu ul li a:hover {
        background-color: #34495e; /* Warna hover */
        border-left-color: #3498db; /* Warna border hover */
    }
    .sidebar-menu ul li a.active {
        background-color: #3498db; /* Warna untuk menu aktif */
        font-weight: bold;
        border-left-color: #ecf0f1; /* Warna border aktif */
    }

    /* Style untuk main content agar tidak tertutup sidebar */
    .main-content {
        margin-left: 250px; /* Lebar sidebar */
        padding: 20px;
    }
</style>

<div class="sidebar">
    <div class="sidebar-header">
        <h2>Panel Servis</h2>
    </div>
    <nav class="sidebar-menu">
        <ul>
            <li>
                <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    Daftar Customer
                </a>
            </li>
            <li>
                <a href="{{ route('service_items.index') }}" class="{{ request()->routeIs('service_items.*') ? 'active' : '' }}">
                    Daftar Barang Servis
                </a>
            </li>
            <li>
                <a href="{{ route('service_processes.index') }}" class="{{ request()->routeIs('service_processes.*') ? 'active' : '' }}">
                    Daftar Proses Servis
                </a>
            </li>
            </ul>
    </nav>
</div>
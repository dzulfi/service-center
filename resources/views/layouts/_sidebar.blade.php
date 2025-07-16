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
        <h2>Service Center</h2>
    </div>
    <nav class="sidebar-menu">
        <ul>
            {{-- Menu untuk semua role yang autentikasi --}}
            @auth
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>
            @endauth

            {{-- CRUD Customer/Pelanggan (admin only) --}}
            @auth
                @if (auth()->user()->isAdmin())
                    <li>
                        <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">
                            Daftar Customer
                        </a>
                    </li>
                @endif
            @endauth

            {{-- CRUD Daftar Barang yang diservice (admin only) --}}
            @auth
                @if (auth()->user()->isAdmin())
                    <li>
                        <a href="{{ route('service_items.index') }}" class="{{ request()->routeIs('service_items.*') ? 'active' : '' }}">
                            Daftar Barang Service
                        </a>
                    </li>
                @endif
            @endauth

            {{-- Admin: Fitur pengiriman barang --}}
            @auth
                @if (auth()->user()->isAdmin())
                    <li class="sidebar-menu-header" style="padding: 10px 20px; font-size: 0.9em; text-transform: uppercase; color: #bbb; margin-top: 15px;">
                        Logistik Admin
                    </li>
                    <li>
                        <a href="{{ route('shipments.admin.outbound_to_rma.index') }}" class="{{ request()->routeIs('shipments.admin.outbound_to_rma.*') ? 'active' : '' }}">
                            Kirim Barang ke RMA
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shipments.admin.inbound_from_rma.index') }}" class="{{ request()->routeIs('shiments.admin.inbound_from_rma.*') ? 'active' : '' }}">
                            Barang Masuk Dari RMA
                        </a>
                    </li>
                @endif
            @endauth

            {{-- RMA: Fitur Pengiriman Barang (RMA) & Proses Service --}}
            {{-- mengerjakan daftar proses service (RMA only) --}}
            @auth
                @if (auth()->user()->isRma())
                    <li>
                        <a href="{{ route('spareparts.index') }}" class="{{ request()->routeIs('spareparts.*') ? 'active' : '' }}">
                            Sparepart Service
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shipments.rma.inbound_from_admin.index') }}" class="{{ request()->routeIs('shipments.rma.inbound_from_admin.*') ? 'active' : '' }}">
                            Barang Masuk Dari Admin
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('service_processes.index') }}" class="{{ request()->routeIs('service_processes.*') ? 'active' : '' }}">
                            Antrian Service
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shipments.rma.outbound_from_rma.index') }}" class="{{ request()->routeIs('shipments.rma.outbound_from_rma.*') ? 'active' : '' }}">
                            Siap Kirim Balik Ke Admin
                        </a>
                    </li>
                @endif
            @endauth

            {{-- Developer: membuat user baru --}}
            @auth
                @if (auth()->user()->isDeveloper())
                    <li>
                        <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                            Manajemen User
                        </a>
                    </li>
                @endif
            @endauth

            {{-- Developer, Superadmin: CRUD kantor Cabang --}}
            @auth
                @if (auth()->user()->isDeveloper() || auth()->user()->isSuperAdmin())
                    <li>
                        <a href="{{ route('branch_offices.index') }}" class="{{ request()->routeIs('branch_offices.*') ? 'active' : '' }}">
                            Kantor Cabang
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('merks.index') }}" class="{{ request()->routeIs('merks.*') ? 'active' : '' }}">
                            Merk
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('item_types.index') }}" class="{{ request()->routeIs('item_types.*') ? 'active' : '' }}">
                            Tipe Barang
                        </a>
                    </li>
                @endif
            @endauth

            {{-- Developer & Superadmin: melihat aktivitas service (semua proces) --}}
            @auth
                @if (auth()->user()->isDeveloper() || auth()->user()->isSuperAdmin())
                    <li>
                        <a href="{{ route('activity.customers.index') }}" class="{{ request()->routeIs('activity.customers.*') ? 'active' : '' }}">
                            Aktivitas Customer
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('activity.service_items.index') }}" class="{{ request()->routeIs('activity.service_items.*') ? 'active' : '' }}">
                            Aktivitas Barang Service
                        </a>
                    </li>
                @endif
            @endauth

            {{-- logout --}}
            @auth
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline">
                        @csrf
                        <button type="submit" style="background: none; border: none; color:white; padding:12px 20px; text-align:left; width:100%; cursor:pointer; font-size: 1.1em; ">
                            Logout
                        </button>
                    </form>
                </li>
            @endauth

            {{-- <li>
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
            </li> --}}
        </ul>
    </nav>
</div>
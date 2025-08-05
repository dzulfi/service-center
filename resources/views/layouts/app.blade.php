<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}
        <title>Service Center</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

        {{-- Select2 --}}
        <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />

        {{-- DataTable Laravel --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">

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
        {{-- <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script> --}}
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="{{ asset('js/select2.min.js') }}"></script>

        {{-- DataTable Laravel --}}
        <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

        {{-- select2 --}}
        <script>
            jQuery(document).ready(function($) {
                // Customer
                $('#customer_id').select2({
                    placeholder: "-- Pilih Mitra Bisnis --",
                    language: "id"
                });

                // Dynamic Options Item Type
                $('#item_type_id').select2({
                    tags: true,
                    placeholder: 'Pilih atau buat Tipe Barang',
                    ajax: {
                        url: '/api/item-types',
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: data.map(item => ({
                                    id: item.id,
                                    text: item.type_name
                                }))
                            };
                        },
                        cache: true
                    },
                    createTag: function (params) {
                        return {
                            id: params.term,
                            text: params.term,
                            newOption: true
                        };
                    },
                    templateResult: function (data) {
                        var $result = $("<span></span>");
                        $result.text(data.text);
                        if (data.newOption) {
                            $result.append(" <em>(buat baru)</em>");
                        }
                        return $result;
                    }
                });

                // Dynamic Options merk
                $('#merk_id').select2({
                    tags: true,
                    placeholder: 'Pilih atau buat Merk Barang',
                    ajax: {
                        url: '/api/merks',
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: data.map(item => ({
                                    id: item.id,
                                    text: item.merk_name
                                }))
                            };
                        },
                        cache: true
                    },
                    createTag: function (params) {
                        return {
                            id: params.term,
                            text: params.term,
                            newOption: true
                        };
                    },
                    templateResult: function (data) {
                        var $result = $("<span></span>");
                        $result.text(data.text);
                        if (data.newOption) {
                            $result.append(" <em>(buat baru)</em>");
                        }
                        return $result;
                    }
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

                // Branch Office
                $('#branch_office_id').select2({
                    placeholder: "-- Pilih Kantor Cabang --",
                    language: "id"
                });
            });
        </script>

        {{-- DataTable --}}
        <script>
            $(document).ready(function() {
                $('#data-tables').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('customers.data') }}", // pastikan route ini benar
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'name', name: 'name' },
                        { data: 'code', name: 'code' },
                        { data: 'phone_number', name: 'phone_number' },
                        { data: 'company', name: 'company' },
                        { data: 'kota', name: 'kota' },
                        { data: 'action', name: 'action', orderable: false, searchable: false },
                    ]
                });
            });
        </script>

        {{-- Multiple choice shipment Admin Branch to RMA--}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const selectAll = document.getElementById('select_all');
                if (selectAll) {
                    selectAll.addEventListener('click', function (e) {
                        const checkboxes = document.querySelectorAll('input[name="service_item_ids[]"]');
                        checkboxes.forEach(cb => cb.checked = e.target.checked);
                    });
                }
            });
        </script>

        {{-- Multiple choice shipment RMA to Admin Branch --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const selectAll = document.getElementById('shipment-from-rma');
                const checkboxes = document.querySelectorAll('input[name="service_item_ids[]"]');
                const form = document.querySelector('form');

                let selectedBranchId = null;

                checkboxes.forEach(cb => {
                    cb.addEventListener('change', function () {
                        const branchId = this.dataset.branchId;

                        if (this.checked) {
                            if (!selectedBranchId) {
                                selectedBranchId = branchId; // set awal
                            } else if (branchId !== selectedBranchId) {
                                this.checked = false;
                                alert('Kantor cabang harus sama untuk semua barang service yang dikirim.');
                            }
                        } else {
                            // Jika semua tidak dicentang, reset branch ID
                            const anyChecked = Array.from(checkboxes).some(box => box.checked);
                            if (!anyChecked) {
                                selectedBranchId = null;
                            }
                        }
                    });
                });

                if (selectAll) {
                    selectAll.addEventListener('click', function (e) {
                        selectedBranchId = null;
                        const branchMap = new Map();

                        checkboxes.forEach(cb => {
                            const branchId = cb.dataset.branchId;
                            if (!branchMap.has(branchId)) {
                                branchMap.set(branchId, []);
                            }
                            branchMap.get(branchId).push(cb);
                        });

                        if (branchMap.size > 1) {
                            alert('Terdapat lebih dari satu kantor cabang. Harap Pilih manual untuk satu cabang saja.')
                            e.preventDefault();
                            return;
                        }

                        checkboxes.forEach(cb => cb.checked = e.target.checked);
                        selectedBranchId = checkboxes[0]?.dataset.branchId || null;
                    });
                }

                // Validasi submit (jika mau tambahan proteksi)
                form.addEventListener('submit', function (e) {
                    const checked = Array.from(checkboxes).filter(cb => cb.checked);
                    const branches = [...new Set(checked.map(cb => cb.dataset.branchId))];
                    if (branches.length > 1) {
                        alert('Kantor cabang harus sama untuk semua barang yang dikirim.');
                        e.preventDefault();
                    }
                });
            });
        </script>
    </body>
</html>
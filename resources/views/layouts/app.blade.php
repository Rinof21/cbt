<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" x-data="{ sidebarOpen: false }" :class="{ 'dark': localStorage.getItem('theme') === 'dark' }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CBT Lab Monitor') }} - @yield('title', 'Dashboard')</title>
    <meta name="description" content="Sistem Manajemen & Kontrol Lab CBT - Monitor status 88 unit PC secara real-time">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <style>
        body { font-family: "Inter", sans-serif; }
        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
            text-decoration: none;
        }
        .sidebar-item:hover { background: rgba(255, 255, 255, 0.1); color: #ffffff; }
        .sidebar-item.active { background: #2563eb; color: #ffffff; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3); }
        .sidebar-item.inactive { color: #94a3b8; }
        .sidebar-item svg { flex-shrink: 0; width: 1.25rem; height: 1.25rem; }

        /* DataTables Custom Tailwind Styling */
        .dataTables_wrapper { padding: 1rem; }
        .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter { margin-bottom: 0.75rem; }
        .dataTables_wrapper .dataTables_length select {
            border-radius: 0.75rem;
            padding: 0.375rem 2rem 0.375rem 0.75rem;
            border: 1px solid #cbd5e1;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 0.75rem;
            padding: 0.375rem 0.75rem;
            border: 1px solid #cbd5e1;
            font-size: 0.875rem;
            margin-left: 0.5rem;
            outline: none;
        }
        .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_paginate {
            margin-top: 0.75rem;
            font-size: 0.75rem;
            color: #64748b;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.25rem 0.625rem;
            margin: 0 0.125rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #334155 !important;
            cursor: pointer;
            font-weight: 600;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #2563eb !important;
            color: #ffffff !important;
            border-color: #2563eb !important;
        }
        .dark .dataTables_wrapper .dataTables_length select,
        .dark .dataTables_wrapper .dataTables_filter input {
            background-color: #334155;
            color: #f8fafc;
            border-color: #475569;
        }
        .dark .dataTables_wrapper .dataTables_info,
        .dark .dataTables_wrapper .dataTables_length,
        .dark .dataTables_wrapper .dataTables_filter { color: #94a3b8; }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            background: #1e293b;
            border-color: #334155;
            color: #cbd5e1 !important;
        }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #2563eb !important;
            color: #ffffff !important;
        }
    </style>
</head>
<body class="h-full bg-slate-100 dark:bg-slate-900 antialiased text-slate-800 dark:text-slate-100">

<div class="min-h-screen bg-slate-100 dark:bg-slate-900">

    {{-- Mobile Backdrop Overlay --}}
    <div x-show="sidebarOpen"
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 md:hidden"
         style="display: none;"></div>

    {{-- Sidebar --}}
    <aside class="w-64 bg-slate-900 dark:bg-slate-950 flex flex-col fixed inset-y-0 left-0 z-50 shadow-2xl transition-transform duration-300 ease-in-out md:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : 'max-md:-translate-x-full'">

        {{-- Logo Header --}}
        <div class="flex items-center justify-between px-5 py-5 border-b border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-white font-black text-base leading-tight tracking-wide">CBT Lab</h1>
                    <p class="text-slate-400 text-[11px] font-medium">Monitor & Control</p>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white p-1 rounded-lg">
                ✕
            </button>
        </div>

        {{-- Navigation Menu --}}
        <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider px-3 mb-2">MENU UTAMA</p>

            <a href="{{ route('dashboard') }}"
               class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : 'inactive' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('floor-plan') }}"
               class="sidebar-item {{ request()->routeIs('floor-plan') ? 'active' : 'inactive' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                </svg>
                Denah Lab
            </a>

            <a href="{{ route('sessions.index') }}"
               class="sidebar-item {{ request()->routeIs('sessions.*') ? 'active' : 'inactive' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Sesi CBT
            </a>

            <a href="{{ route('issues.index') }}"
               class="sidebar-item {{ request()->routeIs('issues.*') ? 'active' : 'inactive' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Tiket Kerusakan
            </a>

            <a href="{{ route('reports.index') }}"
               class="sidebar-item {{ request()->routeIs('reports.*') ? 'active' : 'inactive' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Laporan & Analitik
            </a>

            @if(auth()->user()->can('manage_computers') || auth()->user()->can('manage_users'))
            <div class="pt-4">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider px-3 mb-2">ADMINISTRASI</p>
                @can('manage_computers')
                <a href="{{ route('computers.index') }}"
                   class="sidebar-item {{ request()->routeIs('computers.*') ? 'active' : 'inactive' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2"/>
                    </svg>
                    Manajemen PC
                </a>
                @endcan

                @can('manage_users')
                <a href="{{ route('users.index') }}"
                   class="sidebar-item {{ request()->routeIs('users.*') ? 'active' : 'inactive' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Pengguna
                </a>

                <a href="{{ route('roles.index') }}"
                   class="sidebar-item {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'active' : 'inactive' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Peran & Izin
                </a>
                @endcan
            </div>
            @endif
        </nav>

        {{-- User Profile Footer --}}
        <div class="px-3 py-4 border-t border-slate-800 bg-slate-900/50">
            <div class="flex items-center gap-3 px-2 py-1.5">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-extrabold text-sm shadow-md shrink-0">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-bold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-slate-400 text-[11px] truncate">{{ Auth::user()->getRoleNames()->first() }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit"
                    class="w-full sidebar-item inactive text-left hover:bg-red-500/20 hover:text-red-300">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content Container (Pushed 256px on Desktop) --}}
    <div class="flex-1 pl-0 md:pl-64 flex flex-col min-h-screen w-full min-w-0">

        {{-- Top Header Bar --}}
        <header class="bg-white dark:bg-slate-800 shadow-xs sticky top-0 z-30 border-b border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between px-4 sm:px-6 py-3.5">
                <div class="flex items-center gap-3 min-w-0">
                    {{-- Mobile Hamburger Toggle --}}
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition" aria-label="Buka Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div class="min-w-0">
                        <h2 class="text-base sm:text-lg font-bold text-slate-800 dark:text-slate-100 truncate">@yield('title', 'Dashboard')</h2>
                        <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 truncate">@yield('subtitle', '')</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    {{-- Dark Mode Toggle --}}
                    <button onclick="toggleDark()" class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg class="w-5 h-5 hidden dark:block text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>
                    <span class="text-[11px] sm:text-xs font-semibold text-slate-500 dark:text-slate-400 hidden xs:inline" id="current-time"></span>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="mx-4 sm:mx-6 mt-4 flex items-center gap-3 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-4 py-3 rounded-xl shadow-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-xs sm:text-sm font-medium">{{ session('success') }}</span>
            <button @click="show = false" class="ml-auto text-green-600 dark:text-green-400 hover:text-green-800">✕</button>
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="mx-4 sm:mx-6 mt-4 flex items-center gap-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-200 px-4 py-3 rounded-xl shadow-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-xs sm:text-sm font-medium">{{ session('error') }}</span>
        </div>
        @endif

        {{-- Page Main Content --}}
        <main class="flex-1 p-4 sm:p-6">
            @yield('content')
        </main>

        {{-- Page Footer --}}
        <footer class="border-t border-slate-200 dark:border-slate-700 py-3 px-6 bg-white/50 dark:bg-slate-800/50">
            <p class="text-xs text-slate-400 text-center font-medium">CBT Lab Monitor v1.0 &mdash; {{ now()->format('Y') }}</p>
        </footer>
    </div>
</div>

@livewireScripts

<script>
function toggleDark() {
    const isDark = localStorage.getItem('theme') === 'dark';
    localStorage.setItem('theme', isDark ? 'light' : 'dark');
    document.documentElement.classList.toggle('dark', !isDark);
}
if (localStorage.getItem('theme') === 'dark') {
    document.documentElement.classList.add('dark');
}
function updateClock() {
    const el = document.getElementById('current-time');
    if (el) el.textContent = new Date().toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });
}
setInterval(updateClock, 1000);
updateClock();

// Global SweetAlert2 Form Confirmation Helper
function confirmForm(event, options = {}) {
    event.preventDefault();
    const form = event.target.closest('form') || event.target;
    const isDark = document.documentElement.classList.contains('dark');

    Swal.fire({
        title: options.title || 'Konfirmasi Tindakan',
        text: options.text || 'Apakah Anda yakin ingin melanjutkan?',
        icon: options.icon || 'warning',
        showCancelButton: true,
        confirmButtonColor: options.confirmButtonColor || '#dc2626',
        cancelButtonColor: options.cancelButtonColor || '#64748b',
        confirmButtonText: options.confirmButtonText || 'Ya, Lanjutkan!',
        cancelButtonText: options.cancelButtonText || 'Batal',
        reverseButtons: true,
        background: isDark ? '#1e293b' : '#ffffff',
        color: isDark ? '#f8fafc' : '#0f172a',
        customClass: {
            popup: 'rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700',
            title: 'font-bold text-lg',
            confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-sm shadow-md cursor-pointer',
            cancelButton: 'px-5 py-2.5 rounded-xl font-bold text-sm cursor-pointer'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
}

// Global DataTables Initialization
$(document).ready(function() {
    if ($.fn.DataTable) {
        $.fn.dataTable.ext.errMode = 'none';
    }
    if ($.fn.DataTable && $('.datatable').length) {
        $('.datatable').each(function() {
            const hasColspan = $(this).find('tbody tr td[colspan]').length > 0;
            if (!hasColspan && !$.fn.DataTable.isDataTable(this)) {
                const table = $(this).DataTable({
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 s/d 0 dari 0 data",
                        infoFiltered: "(disaring dari _MAX_ total data)",
                        paginate: {
                            first: "«",
                            last: "»",
                            next: "›",
                            previous: "‹"
                        },
                        emptyTable: "Tidak ada data yang tersedia"
                    },
                    pageLength: 10,
                    responsive: true,
                    order: [],
                    columnDefs: [
                        { orderable: false, targets: [0, -1] }
                    ]
                });

                table.on('order.dt search.dt', function () {
                    let i = 1;
                    table.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
                        this.data(i++);
                    });
                }).draw();
            }
        });
    }
});
</script>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 3500,
        timerProgressBar: true,
        showConfirmButton: false,
        toast: true,
        position: 'top-end',
        background: isDark ? '#1e293b' : '#ffffff',
        color: isDark ? '#f8fafc' : '#0f172a',
        iconColor: '#22c55e'
    });
});
</script>
@endif

@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: "{{ session('error') }}",
        confirmButtonText: 'Tutup',
        confirmButtonColor: '#ef4444',
        background: isDark ? '#1e293b' : '#ffffff',
        color: isDark ? '#f8fafc' : '#0f172a',
    });
});
</script>
@endif

@stack('scripts')
</body>
</html>

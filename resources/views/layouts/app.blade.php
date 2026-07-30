<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white transition-all duration-300">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SDN Cibodas') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700|nunito:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --sidebar-width: 16rem; /* Default Expanded */
        }
        
        #logo-sidebar { 
            width: var(--sidebar-width) !important;
            transition: width 0.3s ease, transform 0.3s ease;
        }
        
        #main-content { 
            margin-left: 0;
            transition: margin-left 0.3s ease;
        }

        @media (min-width: 640px) {
            #main-content,
            #logo-sidebar + div,
            #logo-sidebar + main { 
                margin-left: var(--sidebar-width) !important;
            }
        }

        /* Hide text when mini */
        .sidebar-mini {
            --sidebar-width: 4.5rem;
        }
        
        .sidebar-mini #logo-text,
        .sidebar-mini .sidebar-text { 
            display: none !important; 
        }
        
        .sidebar-mini aside ul li a { 
            justify-content: center !important; 
            padding-left: 0 !important; 
            padding-right: 0 !important; 
        }
        
        .sidebar-mini #logo-sidebar .ms-3 {
            margin-left: 0 !important;
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-gray-900 bg-white">

    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-100">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    <button id="toggle-sidebar-mobile" data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                    <!-- Desktop Toggle Button -->
                    <button id="toggle-sidebar-desktop" type="button" class="hidden sm:inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 me-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    </button>
                    <a href="{{ route('dashboard') }}" class="flex ms-2 md:me-24 items-center">
                        <img src="{{ asset('images/rv,cibodas.png') }}" class="h-8 me-3" alt="Logo SDN Cibodas" />
                        <span id="logo-text" class="self-center text-xl font-bold sm:text-2xl whitespace-nowrap dark:text-white transition-all duration-300">SDN Cibodas</span>
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ms-3 relative">
                        <div>
                            <button type="button" class="flex items-center gap-2 text-sm bg-transparent rounded-full focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 transition" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <!-- Added text info beside avatar for large screens -->
                                <div class="hidden md:flex flex-col text-right">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white leading-tight">{{ auth()->user()->nama_lengkap ?? 'Guest User' }}</span>
                                    <span class="text-xs text-blue-600 dark:text-blue-400 capitalize font-medium">{{ auth()->user()->role ?? 'Guest' }}</span>
                                </div>
                                <img class="w-9 h-9 rounded-full bg-blue-50 object-cover border-2 border-white shadow-sm" src="https://api.dicebear.com/9.x/micah/svg?seed={{ auth()->user()->username ?? 'default' }}&backgroundColor=b6e3f4,c0aede,d1d4f9" alt="user photo">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                        </div>
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 dark:bg-gray-800 dark:divide-gray-700 min-w-[200px]" id="dropdown-user">
                            <div class="px-4 py-3" role="none">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate" role="none">
                                    {{ auth()->user()->nama_lengkap ?? 'Guest User' }}
                                </p>
                                <p class="text-sm text-gray-500 truncate dark:text-gray-400" role="none">
                                    {{ auth()->user()->username ?? '@username' }}
                                </p>
                            </div>
                            <ul class="py-2" role="none">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                    </form>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-gray-700 transition" role="menuitem">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Log out
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-24 transition-all duration-300 -translate-x-full bg-white border-r border-gray-100 sm:translate-x-0" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                            <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                            <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                        </svg>
                        <span class="ms-3 sidebar-text transition-opacity duration-300">Dashboard</span>
                    </a>
                </li>
                
                @if(auth()->user()->role === 'admin')
                <li>
                    <a href="{{ route('admin.guru.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z" clip-rule="evenodd"/></svg>
                        <span class="sidebar-text">Kelola Guru</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.siswa.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 15v3Z" clip-rule="evenodd"/></svg>
                        <span class="sidebar-text">Kelola Siswa</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.kelas.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v10M12 7v10M16 7v10M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/></svg>
                        <span class="sidebar-text">Kelola Kelas</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.mapel.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span class="sidebar-text">Kelola Mapel</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.jadwal.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="sidebar-text">Atur Jadwal</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.kenaikan.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        <span class="sidebar-text">Kenaikan Kelas</span>
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->role === 'guru')
                <li>
                    <a href="{{ route('guru.materi.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M4 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4Zm2 0v16h8V4H6Zm2 3a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1Zm0 4a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1Zm0 4a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1Z" clip-rule="evenodd"/></svg>
                        <span class="sidebar-text">Materi Sekolah</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('guru.tugas.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M8 3a2 2 0 0 0-2 2v1h12V5a2 2 0 0 0-2-2H8ZM4 6h16v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6ZM6 8v11h12V8H6Zm2 2a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1Zm0 4a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1Z" clip-rule="evenodd"/></svg>
                        <span class="sidebar-text">Buat Tugas</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('guru.ujian.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <span class="sidebar-text">Kelola Ujian</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('guru.absensi.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path></svg>
                        <span class="sidebar-text">Kelola Absensi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('guru.laporan.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="sidebar-text">Rekap Laporan</span>
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->role === 'siswa')
                <li>
                    <a href="{{ route('siswa.absensi.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" clip-rule="evenodd"/></svg>
                        <span class="sidebar-text">Absensi Wajah (FR)</span>
                    </a>
                </li>
                
                <li class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200 dark:border-gray-700">
                    <span class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Modul Akademik</span>
                </li>
                <li>
                    <a href="{{ route('siswa.akademik.presensi') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                        <span class="sidebar-text">Rekap Presensi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.akademik.nilai') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        <span class="sidebar-text">Laporan Nilai</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.materi.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M4 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4Zm2 0v16h8V4H6Zm2 3a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1Zm0 4a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1Zm0 4a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1Z" clip-rule="evenodd"/></svg>
                        <span class="sidebar-text">Materi Sekolah</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.tugas.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M8 3a2 2 0 0 0-2 2v1h12V5a2 2 0 0 0-2-2H8ZM4 6h16v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6ZM6 8v11h12V8H6Zm2 2a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1Zm0 4a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1Z" clip-rule="evenodd"/></svg>
                        <span class="sidebar-text">Tugas Sekolah</span>
                    </a>
                </li>
                @endif
            </ul>
            
            <ul class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200 dark:border-gray-700">
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center p-2 text-red-600 transition duration-75 rounded-lg hover:bg-red-50 dark:hover:bg-gray-700 dark:text-red-500 group">
                            <svg class="shrink-0 w-6 h-6 text-red-600 transition duration-75 dark:text-red-500 group-hover:text-red-700 dark:group-hover:text-red-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2"/>
                            </svg>
                            <span class="ms-3 sidebar-text">Logout</span>
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </aside>

    <div id="main-content" class="min-h-screen pt-24 bg-gray-50 transition-all duration-300">
        <div class="p-6">

            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200 dark:bg-gray-800 dark:text-green-400 dark:border-green-800" role="alert" aria-live="polite">
                    <span class="font-medium">Berhasil!</span> {{ session('success') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="p-4 mb-4 text-sm text-amber-800 rounded-lg bg-amber-50 border border-amber-200 dark:bg-gray-800 dark:text-amber-400 dark:border-amber-800" role="alert" aria-live="polite">
                    <span class="font-medium">Perhatian!</span> {{ session('warning') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert" aria-live="polite">
                    <span class="font-medium">Gagal!</span> {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert" aria-live="polite">
                    <span class="font-medium">Periksa kembali isian berikut:</span>
                    <ul class="list-disc pl-5 mt-1.5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    @if (session('open_modal'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modalId = @json(session('open_modal'));
            var modalEl = document.getElementById(modalId);
            if (!modalEl) return;

            // Flowbite v3 mengekspos Modal secara global lewat bundle CDN.
            if (typeof Modal !== 'undefined') {
                new Modal(modalEl, { backdrop: 'static' }).show();
                return;
            }

            // Fallback: klik tombol pemicunya.
            var trigger = document.querySelector('[data-modal-toggle="' + modalId + '"]');
            if (trigger) trigger.click();
        });
    </script>
    @endif

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.form-delete').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan! Akun login terkait juga akan ikut terhapus.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });
        });
    </script>
    <script>
        // Global Password Toggle Logic
        document.addEventListener('click', function(e) {
            if (e.target.closest('.password-toggle')) {
                const button = e.target.closest('.password-toggle');
                const targetId = button.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const eyeIcon = button.querySelector('.eye-icon');
                const eyeOffIcon = button.querySelector('.eye-off-icon');

                if (input.type === 'password') {
                    input.type = 'text';
                    eyeIcon.classList.add('hidden');
                    eyeOffIcon.classList.remove('hidden');
                } else {
                    input.type = 'password';
                    eyeIcon.classList.remove('hidden');
                    eyeOffIcon.classList.add('hidden');
                }
            }
        });
    </script>
    <script>
        // Sidebar Toggle Logic with CSS Variables
        const body = document.body;
        const desktopToggle = document.getElementById('toggle-sidebar-desktop');

        function updateSidebarState(isMini) {
            if (isMini) {
                body.classList.add('sidebar-mini');
                localStorage.setItem('sidebar-mini', 'true');
            } else {
                body.classList.remove('sidebar-mini');
                localStorage.setItem('sidebar-mini', 'false');
            }
        }

        // Initialize state from Storage
        const savedState = localStorage.getItem('sidebar-mini') === 'true';
        updateSidebarState(savedState);

        if (desktopToggle) {
            desktopToggle.addEventListener('click', () => {
                const currentState = body.classList.contains('sidebar-mini');
                updateSidebarState(!currentState);
            });
        }

        // Listen for mobile drawer toggle to handle z-index/overlap if needed
        document.addEventListener('click', (e) => {
            const mobileToggle = e.target.closest('#toggle-sidebar-mobile');
            if (mobileToggle) {
                // Ensure sidebar is expanded when opened on mobile
                if (window.innerWidth < 640) {
                    body.classList.remove('sidebar-mini');
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>

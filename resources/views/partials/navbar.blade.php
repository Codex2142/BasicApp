<nav class="navbar fixed w-full navbar-expand-md navbar-light bg-blue-600 shadow-sm px-4 py-2">
    <div class="d-flex justify-content-between w-100">
        <!-- Hamburger for mobile -->
        <button @click="sidebarOpen = !sidebarOpen" class="d-md-none border-0 bg-transparent">
            <svg class="w-6 h-6 text-dark" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <a class="navbar-brand ms-2" href="/Beranda">MyApp</a>
        <!-- Right Side -->
        <div>
            <!-- Tambahkan dropdown user / logout jika perlu -->
        </div>
    </div>
</nav>

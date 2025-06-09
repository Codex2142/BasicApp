@php
    use App\Models\Transaction;
    $transactionBagde = Transaction::where('status', 'pending')->count();
@endphp
<div class="d-flex flex-column justify-between h-100">
    <!-- BAGIAN ATAS: Menu -->
    <div>
        <h4 class="mb-4">Menu</h4>
        <ul class="nav flex-column gap-2">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('Beranda') ? 'navbar-active' : '' }}" href="/Beranda">
                    <i class="bi bi-house-door-fill"></i> Beranda
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('kalkulator*') ? 'navbar-active' : '' }}" href="/kalkulator">
                    <i class="bi bi-calculator"></i> Kalkulator
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('produk*') ? 'navbar-active' : '' }}" href="/produk">
                    <i class="bi bi-cup-straw"></i> Produk
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('transaksi*') ? 'navbar-active' : '' }}" href="/transaksi">
                    <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
                        <span>
                            <i class="bi bi-newspaper"></i> Transaksi
                        </span>
                        @if ($transactionBagde)
                            <span class="badge rounded-full {{ request()->is('transaksi*') ? 'bg-white text-black' : 'text-slate-800 bg-warning' }}">{{ $transactionBagde }}</span>
                        @endif
                    </div>
                </a>
            </li>

            @auth
                @if (Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('user*') ? 'navbar-active' : '' }}" href="/user">
                            <i class="bi bi-people"></i> Pengguna
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('riwayat*') ? 'navbar-active' : '' }}" href="/riwayat">
                            <i class="bi bi-archive"></i> Riwayat
                        </a>
                    </li>
                @endif
            @endauth

        </ul>
    </div>

    <!-- BAGIAN BAWAH: Setting -->
    <div class="pt-3 border-top">
        <ul class="nav flex-column gap-2">
            <li class="nav-item dropdown dropup">
                <a href="#" class="nav-link bottom-nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i> {{ ucwords(Auth::user()->firstname) }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="/profil">Profil</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="/logout">Keluar</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>

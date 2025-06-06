
<h4 class="mb-4">Menu</h4>
<ul class="nav flex-column gap-3">
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
            <i class="bi bi-newspaper"></i> Transaksi
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->is('user*') ? 'navbar-active' : '' }}" href="/user">
            <i class="bi bi-person-fill"></i> Akun
        </a>
    </li>


</ul>

<aside id="layout-menu" class="layout-menu menu-vertical bg-menu-theme">
    <div class="app-brand demo">
        <span class="app-brand-text demo menu-text fw-bolder ms-2 text-capitalize" style="font-size: 1.1rem; letter-spacing: 0.5px;">Toko Hijau Seller</span>
    </div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon bx bx-home"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('categories*') ? 'active' : '' }}">
            <a href="{{ route('categories.index') }}" class="menu-link">
                <i class="menu-icon bx bx-category"></i>
                <div>Kategori</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('products*') ? 'active' : '' }}">
            <a href="{{ route('products.index') }}" class="menu-link">
                <i class="menu-icon bx bx-box"></i>
                <div>Produk</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('admin/orders*') ? 'active' : '' }}">
            <a href="{{ route('admin.orders.index') }}" class="menu-link">
                <i class="menu-icon bx bx-shopping-bag"></i>
                <div>Pesanan</div>
                @php
                    $pendingCount = \App\Models\Order::where('status_pembayaran', 'pending')->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="badge bg-danger rounded-pill ms-auto">{{ $pendingCount }}</span>
                @endif
            </a>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">Akun</span></li>

        <!-- Logout Direct -->
        <li class="menu-item">
            <form method="POST" action="{{ route('logout') }}" id="logout-form-sidebar">
                @csrf
            </form>
            <a href="javascript:void(0);" class="menu-link" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                <i class="menu-icon tf-icons bx bx-log-out"></i>
                <div>Logout ({{ auth()->user()->name }})</div>
            </a>
        </li>
    </ul>
</aside>

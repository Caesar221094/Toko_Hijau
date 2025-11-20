<aside id="layout-menu" class="layout-menu menu-vertical bg-menu-theme">
    <div class="app-brand demo">
        <span class="app-brand-text demo menu-text fw-bolder ms-2">UTS PPWL</span>
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
    </ul>
</aside>

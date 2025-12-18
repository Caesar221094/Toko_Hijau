<nav class="layout-navbar navbar navbar-expand-xl navbar-detached bg-navbar-theme">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <i class="bx bx-menu bx-sm"></i>
    </div>

    <div class="navbar-nav-right d-flex align-items-center ms-auto">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item">
                <span class="text-muted small">{{ Auth::user()->name }}</span>
            </li>
        </ul>
    </div>

</nav>

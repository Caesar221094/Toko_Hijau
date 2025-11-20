<nav class="layout-navbar navbar navbar-expand-xl navbar-detached bg-navbar-theme">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <i class="bx bx-menu bx-sm"></i>
    </div>

    <div class="navbar-nav-right d-flex align-items-center ms-auto">
        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" class="rounded-circle">
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item">Logout</button>
                        </form>
                    </li>
                </ul>
            </li>

        </ul>
    </div>

</nav>

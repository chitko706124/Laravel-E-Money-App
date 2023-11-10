<div class="col-auto px-0 shadow">

    <div id="sidebar" class="collapse collapse-horizontal show border-end">
        {{-- <div  class=" min-vh-100"> --}}
        <div id="sidebar" class=" vh-100  d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;">

            <ul class="nav nav-pills flex-column gap-2  mb-auto ">
                <h6 class=" fw-bolder text-secondary mb-2">DASHBOARD</h6>

                <li class="nav-item  bg-white">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link  text-dark @yield('dashboard-active') "
                        aria-current="page">
                        <i class="bi bi-pc-display-horizontal"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item bg-white">
                    <a href="{{ route('admin-user.index') }}" class="nav-link text-dark  @yield('admin-user-mana-active')"
                        aria-current="page">
                        <i class="bi bi-person-lock"></i>
                        Admin Management
                    </a>
                </li>

                <li class="nav-item bg-white">
                    <a href="{{ route('user.index') }}" class="nav-link text-dark  @yield('user-mana-active')"
                        aria-current="page">
                        <i class="bi bi-people"></i>
                        User Management
                    </a>
                </li>


                <li class="nav-item bg-white">
                    <a href="{{ route('wallet.index') }}" class="nav-link text-dark  @yield('wallet-active')"
                        aria-current="page">
                        {{-- <i class="bi bi-wallet"></i> --}}
                        <i class="bi bi-wallet-fill"></i>
                        Wallet
                    </a>
                </li>

            </ul>

        </div>


    </div>
</div>

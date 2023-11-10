<header class="py-3  border-bottom shadow  ">
    <div class="container-fluid align-items-center d-flex justify-content-between">
        <div class=" ">
            <div class=" d-flex justify-content-between gap-5 align-items-center">
                <h1 class=" mb-0  " style="font-family: 'Dancing Script', cursive;">
                    <a href="" class=" text-decoration-none ">Dashboard</a>
                </h1>

                <a href="#" data-bs-target="#sidebar" data-bs-toggle="collapse"><i class="bi bi-list"></i></a>
            </div>
        </div>



        <div class=" d-flex align-items-center">

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle"
                    id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::guard('admin_users')->user()->name }}"
                        alt="" width="32" height="32" class="rounded-circle me-2">

                    <strong>{{ Auth::guard('admin_users')->user()->name }}</strong>
                </a>
                <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
                    <li><a class="dropdown-item" href="#">New project...</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="{{ route('admin.logout') }}"
                            onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                            Log out</a></li>

                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </ul>
            </div>
        </div>
    </div>
</header>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container">
        <a class="navbar-brand me-5" href="/">Team - Employee</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Team Management -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle btn text-white border border-primary
                       {{ request()->routeIs('team.*') ? 'active' : '' }}"
                       id="teamDropdown" data-bs-toggle="dropdown" role="button">
                        Team Management
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item {{ request()->routeIs('team.list') ? 'active' : '' }}" href="{{ route('team.list') }}">
                                <i class="bi bi-list"></i> List Team
                            </a></li>
                        <li><a class="dropdown-item {{ request()->routeIs('team.create') ? 'active' : '' }}" href="{{ route('team.create') }}">
                                <i class="bi bi-plus-circle text-success"></i> Create Team
                            </a></li>
                        <li><a class="dropdown-item {{ request()->routeIs('team.search') ? 'active' : '' }}" href="{{ route('team.search') }}">
                                <i class="bi bi-search"></i> Search Team
                            </a></li>
                    </ul>
                </li>

                <!-- Employee Management -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle btn text-white border border-primary
                       {{ request()->routeIs('emp.*') ? 'active' : '' }}"
                       id="employeeDropdown" data-bs-toggle="dropdown" role="button">
                        Employee Management
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item {{ request()->routeIs('emp.list') ? 'active' : '' }}" href="{{ route('emp.list') }}">
                                <i class="bi bi-list"></i> List Employee
                            </a></li>
                        <li><a class="dropdown-item {{ request()->routeIs('emp.create') ? 'active' : '' }}" href="{{ route('emp.create') }}">
                                <i class="bi bi-plus-circle text-success"></i> Create Employee
                            </a></li>
                        <li><a class="dropdown-item {{ request()->routeIs('emp.search') ? 'active' : '' }}" href="{{ route('emp.search') }}">
                                <i class="bi bi-search"></i> Search Employee
                            </a></li>
                    </ul>
                </li>

                <!-- Login / Logout -->
                <li class="nav-item ms-3">
                    @if(Auth::check())
                        <a class="btn btn-danger text-white px-4" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <a class="btn btn-danger text-white px-4" href="{{ route('login.form') }}">
                            Login
                        </a>
                    @endif
                </li>

            </ul>
        </div>
    </div>
</nav>

<!-- CSS -->
<style>
    /* Active */
    .nav-link.active, .dropdown-item.active {
        background-color: #198754 !important;
        color: white !important;
        border-color: #198754;
    }

    /* Hover */
    .nav-link:hover, .dropdown-item:hover {
        background-color: #f5749f !important;
        color: white !important;
    }
</style>

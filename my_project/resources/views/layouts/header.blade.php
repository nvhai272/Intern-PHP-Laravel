
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand me-5" href="/">My Website</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse ms-5" id="navbarNav">
            <ul class="navbar-nav me-auto">

                <li class="nav-item dropdown me-5">

                    <a class="nav-link dropdown-toggle btn text-dark me-3
                     border border-primary"
                       id="teamDropdown" data-bs-toggle="dropdown" role="button"
                       onmouseover="this.classList.add('text-white', 'bg-primary')"
                       onmouseout="this.classList.remove('text-white', 'bg-primary')">
                        Team Management
                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{route('team.list')}}"><i class="bi bi-list"></i> List Team</a>
                        </li>
                        <li><a class="dropdown-item" href="{{route('team.form.create')}}"><i
                                    class="bi bi-plus-circle text-success"></i> Create Team</a></li>
                        <li><a class="dropdown-item" href="{{route('logout')}}"><i class="bi bi-search"></i> Search
                                Team</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown me-5">

                    <a class="nav-link dropdown-toggle btn text-dark me-3
                     border border-primary"
                       id="teamDropdown" data-bs-toggle="dropdown" role="button"
                       onmouseover="this.classList.add('text-white', 'bg-primary')"
                       onmouseout="this.classList.remove('text-white', 'bg-primary')">
                        Employee Management
                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href=""><i class="bi bi-list"></i> List Admin</a>
                        </li>
                        <li><a class="dropdown-item" href=""><i
                                    class="bi bi-plus-circle text-success"></i> Create Admin</a></li>
                        <li><a class="dropdown-item" href=""><i class="bi bi-search"></i> Search
                                Admin</a></li>
                    </ul>
                </li>
                <a class="btn btn-danger text-right" href="#    ">Logout</a>
            </ul>
        </div>
    </div>
</nav>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link text-center">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>
    <div class="sidebar">

        <nav class="mt-4">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget='treeview' role="menu" data-accordion="false">
                @admin()
                <li class="nav-item">
                    <a href="{{ route('dashboard.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{ route('user.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-users nav-icon"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>
                @endadmin
                <li class="nav-item">
                    <a href="{{ route(auth()->user()->user_role . '.project.index') }}" class="nav-link">
                        <i class="fas fa-file-archive nav-icon"></i>
                        <p>
                            Projects &amp; Tasks
                        </p>
                    </a>
                </li>

                <li class="nav-item mb-5">
                    <a href="{{ route('logout') }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

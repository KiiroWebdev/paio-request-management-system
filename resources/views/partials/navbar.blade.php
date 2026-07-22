<nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 py-3">

    {{-- Sidebar Toggle --}}
    <button
        id="sidebarToggle"
        class="btn btn-outline-secondary me-3">

        <i class="bi bi-list fs-4"></i>

    </button>

    {{-- Dynamic Page Title --}}
    <div>

        <h4 class="mb-0 fw-bold">

            @yield('page-title', 'Dashboard')

        </h4>

        <small class="text-muted">

            Welcome back,
            {{ Auth::user()->name }}

        </small>

    </div>

    <div class="ms-auto d-flex align-items-center">

        {{-- Search --}}
        <div
            class="input-group me-3"
            style="width:320px;">

            <span class="input-group-text bg-white">

                <i class="bi bi-search"></i>

            </span>

            <input
                class="form-control"
                placeholder="Search...">

        </div>

        {{-- Notifications --}}
        <button
            class="btn btn-light position-relative me-3">

            <i class="bi bi-bell fs-5"></i>

            <span
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

                3

            </span>

        </button>

        {{-- User Dropdown --}}
        <div class="dropdown">

            <button
                class="btn btn-light dropdown-toggle d-flex align-items-center"
                data-bs-toggle="dropdown">

                <i class="bi bi-person-circle me-2 fs-5"></i>

                {{ Auth::user()->name }}

            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow">

                <li>

                    <h6 class="dropdown-header">

                        {{ Auth::user()->name }}

                    </h6>

                </li>

                <li>

                    <a
                        class="dropdown-item"
                        href="{{ route('profile.edit') }}">

                        <i class="bi bi-person me-2"></i>

                        My Profile

                    </a>

                </li>

                <li>

                    <a
                        class="dropdown-item"
                        href="#">

                        <i class="bi bi-key me-2"></i>

                        Change Password

                    </a>

                </li>

                <li>

                    <hr class="dropdown-divider">

                </li>

                <li>

                    <form
                        method="POST"
                        action="{{ route('logout') }}">

                        @csrf

                        <button
                            class="dropdown-item text-danger">

                            <i class="bi bi-box-arrow-right me-2"></i>

                            Logout

                        </button>

                    </form>

                </li>

            </ul>

        </div>

    </div>

</nav>
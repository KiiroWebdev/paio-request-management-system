<aside id="sidebar" class="sidebar">

    <!-- Logo -->
    <div class="sidebar-header">

        <div class="logo-group">

            <img src="{{ asset('images/city-logo.png') }}"
                 alt="City Logo"
                 class="sidebar-logo">

            <img src="{{ asset('images/paio-logo.png') }}"
                 alt="PAIO Logo"
                 class="sidebar-logo">

        </div>

        <h5>City Government</h5>

        <h6>San Pedro, Laguna</h6>

        <small>Public Affairs & Information Office</small>

    </div>

    <!-- Navigation -->

    <nav class="sidebar-menu">

    {{-- Dashboard --}}
    <a href="{{ route('dashboard') }}"
       class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">

        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>

    </a>

    {{-- Request Management --}}
    <div class="menu-group">

        <button class="menu-toggle">

            <div>

                <i class="bi bi-folder2-open"></i>

                <span>Request Management</span>

            </div>

            <i class="bi bi-chevron-down arrow"></i>

        </button>

        <div class="submenu-group">

            <a href="{{ route('request-wizard.services') }}">
                <i class="bi bi-plus-circle"></i>
                <span>New Request</span>
            </a>

           <a href="{{ route('requests.incoming') }}">
    <i class="bi bi-inbox"></i>
    <span>Incoming</span>
</a>
@if(auth()->user()->role === 'Admin')

    <a href="{{ route('request-approvals.index') }}"
       class="{{ request()->routeIs('request-approvals.*') ? 'active' : '' }}">

        <i class="bi bi-person-check"></i>
        <span>Request Approvals</span>

    </a>

@endif

<a href="{{ route('requests.ongoing') }}">
    <i class="bi bi-hourglass-split"></i>
    <span>Ongoing</span>
</a>

<a href="{{ route('requests.completed') }}">
    <i class="bi bi-check-circle"></i>
    <span>Completed</span>
</a>

<a href="{{ route('requests.archive.index') }}">
    <i class="bi bi-archive"></i>
    <span>Archive</span>
</a>

        </div>

    </div>

    @if(Auth::user()->isAdmin())

    {{-- Master Data --}}
    <div class="menu-group">

        <button class="menu-toggle">

            <div>
                <i class="bi bi-grid"></i>
                <span>Master Data</span>
            </div>

            <i class="bi bi-chevron-down arrow"></i>

        </button>

        <div class="submenu-group">

            <a href="{{ route('services.index') }}">
                <i class="bi bi-grid-3x3-gap"></i>
                <span>Services</span>
            </a>

            <a href="{{ route('organizations.index') }}">
                <i class="bi bi-buildings"></i>
                <span>Organizations</span>
            </a>

            <a href="{{ route('contacts.index') }}">
                <i class="bi bi-person-lines-fill"></i>
                <span>Contacts</span>
            </a>

        </div>

    </div>

@endif
@if(Auth::user()->isAdmin())

    {{-- Administration --}}
    <div class="menu-group">

        <button class="menu-toggle">

            <div>
                <i class="bi bi-gear"></i>
                <span>Administration</span>
            </div>
 
            <i class="bi bi-chevron-down arrow"></i>

        </button>

        <div class="submenu-group">

            <a href="{{ route('users.index') }}">
                <i class="bi bi-people"></i>
                <span>Users</span>
            </a>

            <a href="{{ route('request-action-requests.index') }}">
    <i class="bi bi-check2-square"></i>
    <span>Request Action Requests</span>
</a>

            <a href="#">
                <i class="bi bi-bar-chart"></i>
                <span>Reports</span>
            </a>

            <a href="#">
                <i class="bi bi-clock-history"></i>
                <span>Activity Logs</span>
            </a>

            <a href="#">
                <i class="bi bi-sliders"></i>
                <span>Settings</span>
            </a>

        </div>

    </div>

@endif

</nav>

    <!-- Footer -->

    <div class="sidebar-footer">

        <div class="avatar">

            {{ strtoupper(substr(Auth::user()->name,0,1)) }}

        </div>

        <div>

            <strong>{{ Auth::user()->name }}</strong>

            <small>{{ Auth::user()->role }}</small>
        </div>

    </div>

</aside>
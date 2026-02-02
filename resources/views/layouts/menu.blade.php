
<li class="nav-item">
    <a href="{{ route('vaccinations.index') }}"
       class="nav-link {{ Request::is('vaccinations*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-syringe"></i>
        <p>Vaccinations</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('certificates.index') }}"
       class="nav-link {{ Request::is('certificates*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-certificate"></i>
        <p>Certificates</p>
    </a>
</li>

@if(Auth::user()->role_id == 1 OR Auth::user()->role_id == 2)
    <li class="nav-item">
        <a href="{{ route('clients.index') }}"
           class="nav-link {{ Request::is('clients*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>Clients</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('vaccines.index') }}"
           class="nav-link {{ Request::is('vaccines*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-vial"></i>
            <p>Vaccines</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('countries.index') }}"
           class="nav-link {{ Request::is('countries*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-globe"></i>
            <p>Countries</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('provinces.index') }}"
           class="nav-link {{ Request::is('provinces*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-map-marked-alt"></i>
            <p>Provinces</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('districts.index') }}"
           class="nav-link {{ Request::is('districts*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-map-marker-alt"></i>
            <p>Districts</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('facilities.index') }}"
           class="nav-link {{ Request::is('facilities*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-hospital"></i>
            <p>Facilities</p>
        </a>
    </li>
@endif

@if(Auth::user()->role_id == 1)
    <li class="nav-item">
        <a href="{{ route('roles.index') }}"
           class="nav-link {{ Request::is('roles*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-tag"></i>
            <p>Roles</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('users.index') }}"
           class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-shield"></i>
            <p>Users</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.test-sms') }}"
           class="nav-link {{ Request::is('admin/test-sms*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-sms"></i>
            <p>SMS Testing</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.logs') }}"
           class="nav-link {{ Request::is('admin/logs*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-list-alt"></i>
            <p>Log Viewer</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.system-tools') }}"
           class="nav-link {{ Request::is('admin/system-tools*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-cogs"></i>
            <p>System Tools</p>
        </a>
    </li>
@endif



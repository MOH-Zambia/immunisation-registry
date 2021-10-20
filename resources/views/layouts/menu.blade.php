<li class="nav-item">
    <a href="{{ route('roles.index') }}"
       class="nav-link {{ Request::is('roles*') ? 'active' : '' }}">
        <p>Roles</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('users.index') }}"
       class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
        <p>Users</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('clients.index') }}"
       class="nav-link {{ Request::is('clients*') ? 'active' : '' }}">
        <p>Clients</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('vaccinations.index') }}"
       class="nav-link {{ Request::is('vaccinations*') ? 'active' : '' }}">
        <p>Vaccinations</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('certificates.index') }}"
       class="nav-link {{ Request::is('certificates*') ? 'active' : '' }}">
        <p>Certificates</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('vaccines.index') }}"
       class="nav-link {{ Request::is('vaccines*') ? 'active' : '' }}">
        <p>Vaccines</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('countries.index') }}"
       class="nav-link {{ Request::is('countries*') ? 'active' : '' }}">
        <p>Countries</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('provinces.index') }}"
       class="nav-link {{ Request::is('provinces*') ? 'active' : '' }}">
        <p>Provinces</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('districts.index') }}"
       class="nav-link {{ Request::is('districts*') ? 'active' : '' }}">
        <p>Districts</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('facilities.index') }}"
       class="nav-link {{ Request::is('facilities*') ? 'active' : '' }}">
        <p>Facilities</p>
    </a>
</li>



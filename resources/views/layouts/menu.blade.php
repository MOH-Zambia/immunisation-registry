<li class="nav-item">
    <a href="{{ route('facilities.index') }}"
       class="nav-link {{ Request::is('facilities*') ? 'active' : '' }}">
        <p>Facilities</p>
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



<div class="sidebar">
    <div class="logo">
        <img src="{{ asset('https://faq.asabri.co.id/assets/images/logo_asabri_2.svg') }}" alt="Logo"
            style="height: 100px;">
            <p style="font-size: 70;">Monitoring & Laporan Investasi</p>
    </div>

    <nav>
        <!--<a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Dashboard
        </a>-->

        <a href="{{ route('overview') }}" class="nav-link {{ request()->routeIs('overview') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> Overview
        </a>

        <a href="{{ route('fundfactsheet.index') }}"
            class="nav-link {{ request()->routeIs('fundfactsheet.*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i> Fund Fact Sheet
        </a>

        <a href="{{ route('laporan.mingguan.index') }}"
            class="nav-link {{ request()->routeIs('laporan.mingguan.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-week"></i> Laporan Mingguan
        </a>

        <a href="{{ route('laporan.bulanan.index') }}"
            class="nav-link {{ request()->routeIs('laporan.bulanan.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i> Laporan Bulanan
        </a>

        <!--<a href="{{ route('laporan.tahunan.index') }}"
            class="nav-link {{ request()->routeIs('laporan.tahunan.*') ? 'active' : '' }}">
            <i class="fas fa-calendar"></i> Laporan Tahunan
        </a>-->

        @auth
            @if(auth()->user()->canManage())
                <a href="{{ route('document-categories.index') }}"
                    class="nav-link {{ request()->routeIs('document-categories.*') ? 'active' : '' }}">
                    <i class="fas fa-folder-open"></i> Kelola Kategori
                </a>
            @endif
        @endauth

        @auth
            @if(auth()->user()->role === 'superadmin')
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Manajemen User
                </a>
            @endif
        @endauth
    </nav>
</div>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="fa-regular fa-building"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin Panel</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Management
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('products.index') }}">
            <i class="fa-solid fa-box"></i>
            <span>Products</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('services.index') }}">
            <i class="fa-solid fa-gear"></i>
            <span>Services</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('customers.index') }}">
            <i class="fa-solid fa-users"></i>
            <span>Customers</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('portfolios.index') }}">
            <i class="fa-solid fa-briefcase"></i>
            <span>Portfolio</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('associations.index') }}">
            <i class="fa-solid fa-handshake"></i>
            <span>Associations</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('categories.index') }}">
            <i class="fas fa-fw fa-tags"></i>
            <span>Product Categories</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('kategoris.index') }}">
            <i class="fas fa-fw fa-user-tag"></i>
            <span>Customer Categories</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('groups.index') }}">
            <i class="fas fa-fw fa-layer-group"></i>
            <span>Portfolio Groups</span>
        </a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>
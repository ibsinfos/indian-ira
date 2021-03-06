<nav class="navbar navbar-expand-md fixed-left font-weight-bold">
    <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
        <i class="fa fa-home"></i> Dashboard
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav flex-column">
            <li class="nav-item dropdown">
                <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="navbarDropdown"
                    role="button"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                  <i class="fas fa-cog"></i> Global Settings
                </a>

                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('admin.globalSettings.companyAddress') }}">
                        <i class="fas fa-map-marker-alt"></i> Company Address
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.globalSettings.bank') }}">
                        <i class="fas fa-university"></i> Bank Details
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.globalSettings.paymentOptions') }}">
                        <i class="fas fa-rupee-sign"></i> Payment Options
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.globalSettings.codCharges') }}">
                        <i class="fas fa-rupee-sign"></i> COD Charges
                    </a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.shippingRates') }}">
                    <i class="fas fa-ship"></i> Shipping Rates
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.tags') }}">
                    <i class="fas fa-tags"></i> Tags
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.categories') }}">
                    <i class="fas fa-arrows-alt"></i> Categories
                </a>
            </li>
            <li class="nav-item dropdown">
                <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="navbarDropdown"
                    role="button"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                  <i class="fas fa-cube"></i> Products
                </a>

                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('admin.products') }}">
                        <i class="fas fa-cubes"></i> All Products
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.products.priceAndStock') }}">
                        <i class="fas fa-rupee-sign"></i> Prices And Stock
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.products.enquiries') }}">
                        <i class="fas fa-question-circle"></i> Enquiries
                    </a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.carousels') }}">
                    <i class="fas fa-cubes"></i> Carousels
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.coupons') }}">
                    <i class="fas fa-eraser"></i> Coupons
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.users') }}">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.orders') }}">
                    <i class="fas fa-shopping-cart"></i> Orders
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.logout') }}">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</nav>

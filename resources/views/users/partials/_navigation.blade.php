<nav class="navbar navbar-expand-md fixed-left font-weight-bold">
    <a class="navbar-brand" href="{{ route('users.dashboard') }}">
        <i class="fa fa-home"></i> Dashboard
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('homePage') }}" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Go to Main Site
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.billingAddress') }}">
                    <i class="fas fa-address-card"></i> Billing Address
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
                  <i class="fas fa-cog"></i> Settings
                </a>

                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('users.settings.general') }}">
                        <i class="fas fa-wrench"></i> General
                    </a>
                    <a class="dropdown-item" href="{{ route('users.settings.password') }}">
                        <i class="fas fa-key"></i> Password
                    </a>
                </div>
            </li>
            <li class="nav-item">
                <a href="{{ route('cart.show') }}" class="nav-link">
                    <i class="fas fa-shopping-cart"></i>
                    Cart <span class="badge badge-success cartBadge float-right">
                        {{ \IndianIra\Utilities\Cart::totalProducts() }}
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.wishlist') }}">
                    <i class="fas fa-heart"></i> Wishlist
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.orders') }}">
                    <i class="fas fa-shopping-cart"></i> Orders
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.logout') }}">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</nav>

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
                <a class="nav-link" href="{{ route('users.billingAddress') }}">
                    <i class="fas fa-address-card"></i> Billing Address
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

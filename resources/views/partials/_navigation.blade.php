<nav class="navbar navbar-expand-md font-weight-bold" style="border-bottom: 1px solid #ddd">
    <a class="navbar-brand" href="{{ route('homePage') }}">
        <i class="fa fa-home"></i>
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('homePage') }}">
                    Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0)">
                    About
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0)">
                    Contact Us
                </a>
            </li>
            @if (isset($parentCategoriesInMenu) && $parentCategoriesInMenu->isNotEmpty())
                @foreach ($parentCategoriesInMenu as $category)
                    @if ($category->childs->isNotEmpty())
                        <li class="nav-item dropdown">
                            <a
                                class="nav-link dropdown-toggle"
                                href="javascript:void(0)"
                                id="navbarDropdown"
                                role="button"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                              {{ title_case($category->name) }}
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @foreach ($category->childs as $child)
                                    <a class="dropdown-item" href="javascript:void(0)">
                                        {{ title_case($child->name) }}
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url($category->pageUrl()) }}">
                                {{ title_case($category->name) }}
                            </a>
                        </li>
                    @endif
                @endforeach
            @endif
        </ul>
    </div>
</nav>

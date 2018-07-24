<nav class="navbar navbar-expand-md font-weight-bold" style="border-bottom: 1px solid #ddd">
    <a class="navbar-brand" href="{{ route('homePage') }}">
        <i class="fa fa-home"></i>
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('homePage') }}">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0)">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('tags.index') }}">Tags</a>
            </li>

            <li class="nav-item dropdown">
                <a
                    class="nav-link dropdown-toggle"
                    href="javascript:void(0)"
                    id="navbarDropdownCat"
                    role="button"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    All Categories
                </a>

                @if (isset($allCategoriesInMenu) && $allCategoriesInMenu->isNotEmpty())
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownCat">
                        @foreach ($allCategoriesInMenu as $parent)
                            <li>
                                @if ($parent->childs->isNotEmpty())
                                    <a
                                        class="nav-link dropdown-toggle"
                                        href="javascript:void(0)"
                                        id="navbarDropdownCatSub"
                                        role="button"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                    >
                                        {{ title_case($parent->name) }}
                                    </a>

                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownCatSub">
                                        @foreach ($parent->childs as $subCategory)
                                            <li>
                                                <a class="dropdown-item" href="{{ url($subCategory->pageUrl()) }}">
                                                    {{ title_case($subCategory->name) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <a class="dropdown-item" href="{{ url($parent->pageUrl()) }}">
                                        {{ title_case($parent->name) }}
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
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

                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @foreach ($category->childs as $child)
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)">
                                            {{ title_case($child->name) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
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

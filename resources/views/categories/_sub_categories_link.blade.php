@if ($parentCategory->isNotEmpty())
    <ul class="px-4 py-2 bg-dark">
        @foreach ($parentCategory as $parent)
            <li class="list-unstyled">
                <a href="{{ $parent->pageUrl() }}" class="mainSiteLink text-white">
                    {{ title_case($parent->name) }}
                </a>

                @include('categories._sub_categories_link', [
                    'parentCategory' => $parent->isSuperParent()
                                            ? $category->childs
                                            : $parent->childs
                ])
            </li>
        @endforeach
    </ul>
@endif

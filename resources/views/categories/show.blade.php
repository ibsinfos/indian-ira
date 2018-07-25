@extends('partials._layout')

@section('title_and_meta_info')
    <title>{{ $category->meta_title }}</title>
    <meta name="description" content="{{ $category->meta_description }}" />
    <meta name="keywords" content="{{ $category->meta_keywords }}" />
@endsection

@section('pageStyles')
    <style>
        .productName {
            min-height: 100px;
        }

         @media screen and (max-width: 380px) {
            .col-6 {
                max-width: 100% !important;
                flex: 0 0 100% !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="mb-4"></div>

    <div class="container">
        <nav class="mb-4" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('homePage') }}" class="mainSiteLink">
                        Home
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {!! $category->getBreadCrumb() !!}
                </li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-3">
                {{-- $allCategoriesInMenu coming from \IndianIra\Providers\AppServiceProvider --}}
                @foreach ($allCategoriesInMenu as $parentCategory)
                    <div class="card mb-0">
                        <div
                            class="card-header p-2"
                            @if($parentCategory->id == $category->id || $category->getSuperParent()->id == $parentCategory->id)
                                style="background: #fff !important; font-weight: bold; border-left: 3px solid #f5403a; border-bottom: 0"
                            @else
                                style="background: #f8f9fa !important;"
                            @endif
                        >
                            <a
                                class="mainSiteLink @if($parentCategory->id == $category->getSuperParent()->id) font-weight-bold @endif"
                                href="{{ url($parentCategory->pageUrl()) }}"
                            >
                                {{ title_case($parentCategory->name) }}
                            </a>
                        </div>
                    </div>
                @endforeach

                @include('categories._sub_categories_link', [
                    'parentCategory' => $category->isSuperParent()
                                            ? $category->childs
                                            : $superParentCategory->childs
                ])
            </div>

            <div class="col-md-9">
                <h1 class="display-6 font-weight-bold mb-4 mt-md-0 mt-sm-4" style="font-size: 1.25rem;">
                    Products in {{ title_case($category->name) }}
                </h1>

                <div class="row">
                    @if ($products->isNotEmpty())
                        @foreach ($products as $product)
                            <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                                @include('partials._product_card')
                            </div>
                        @endforeach
                    @else
                        <p class="text-danger ml-3">
                            No Products found in this category.
                        </p>
                    @endif
                </div>

                <div class="mb-4"></div>

                {{ $products->links() }}
            </div>
        </div>
    </div>

    <div class="mb-4"></div>
@endsection

@section('pageScripts')
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>

    <script>
        var $grid = $('.allProductsInCardFormat').masonry({
            itemSelector: '.productBox',
            columnWidth: 200
        });

        $grid.imagesLoaded().progress( function() {
            $grid.masonry('layout');
        });


        $('body').on('click', '.btnAddToCart', function (e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('href'),
                type: 'GET',
                success: function (res) {
                    displayGrowlNotification(res.status, res.title, res.message, res.delay);

                    $('.cartBadge').html(res.count);
                },
                error: function (err) {
                    console.log(err);
                },
            });

            return false;
        });
    </script>
@endsection

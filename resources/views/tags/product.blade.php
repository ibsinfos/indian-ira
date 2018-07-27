@php
$options = $product->options()->onlyEnabled()->get()->sortBy('sort_number');

$option = $options->first();

$price = $option != null ? $option->selling_price : 0.0;
if ($option && $option->discount_price > 0.0) {
    $price = $option->discount_price;
}

$link = route('cart.add', $product->code);
if ($product->number_of_options >= 1) {
    $link = route('cart.add', [
        $product->code, $options->last()->option_code
    ]);
}
@endphp

@extends('partials._layout')

@section('title_and_meta_info')
    <title>{{ $product->meta_title }}</title>
    <meta name="description" content="{{ $product->meta_description }}" />
    <meta name="keywords" content="{{ $product->meta_keywords }}" />

    <link rel="canonical" href="{{ url($product->canonicalPageUrl()) }}" />
@endsection

@section('pageStyles')
    <link rel="stylesheet" href="{{ url('/plugins/owl-carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ url('/plugins/owl-carousel/dist/assets/owl.theme.default.min.css') }}">

    <style>
        .productName {
            min-height: 100px;
        }

        .accordion .card-header {
            cursor: pointer;
        }

        .productAmount {
            font-size: 20px;
        }

        .nav-pills .nav-link {
            font-size: 16px !important;
            color: #1e615d !important;
        }

        .nav-pills .nav-link.active {
            background: #00cbc3 !important;
            color: #fff !important;
        }

        .tab-content .tab-pane.active {
            font-size: 16px;
            line-height: 25px;
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
                <li class="breadcrumb-item">
                    <a href="{{ route('tags.index') }}" class="mainSiteLink">
                        Tags
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url($tag->pageUrl()) }}" class="mainSiteLink">
                        {{ title_case($tag->name) }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ title_case($product->name) }}
                </li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-5">
                <div class="bg-white imgZoomContainer">
                    @if ($product->number_of_options >= 1)
                        <img
                            src="{{ url($option->zoomedImage()) }}"
                            alt="{{ $product->name }}"
                            id="productImage"
                            class="img-fluid"
                            style="margin-bottom: 20px;"
                            data-image-zoom="/{{ $option->zoomedImage() }}"
                        >
                    @else
                        <img
                            src="{{ url($product->zoomedImage()) }}"
                            alt="{{ $product->name }}"
                            id="productImage"
                            class="img-fluid"
                            style="margin-bottom: 20px;"
                            data-image-zoom="/{{ $product->zoomedImage() }}"
                        >
                    @endif
                </div>
            </div>
            <div class="col-md-7">
                <h1 class="display-6 font-weight-bold mb-4 text-justify" style="font-size: 1.9rem;">
                    {{ title_case($product->name) }}
                </h1>

                <h2 class="mb-4" style="font-size: .8rem;">
                    <span class="font-weight-bold">Code:</span> {{ title_case($product->code) }}

                    @if ($product->number_of_options > 0)
                        <br /><br />
                        <span class="font-weight-bold">Option Code:</span>
                        <span class="optionCode">{{ $option->option_code }}</span>
                    @endif
                </h2>

                <div class="mb-3"></div>

                <h2 class="mb-4" style="font-size: .8rem;">
                    <div class="mb-3">
                        <span class="font-weight-bold">Price:</span>
                        <span class="productAmount">
                            <i class="fas fa-rupee-sign"></i>
                            <span class="amount">{{ number_format($price, 2) }}</span>
                        </span>
                    </div>

                    <span class="font-weight-bold">In Stock:</span>
                    <span class="inStock">{{ $option->stock }}</span>

                    <div class="mb-3"></div>

                    @if ($option->stock >= 1)
                        <a href="{{ $link }}" class="btn btn-dark btn-lg btnAddToCart text-uppercase">
                            Add To Cart
                        </a>
                    @else
                        <a href="javascript:void(0)" class="btn btn-dark btn-lg btnAddToCart text-uppercase">
                            Out of Stock
                        </a>
                    @endif

                    <a
                        href="#"
                        class="btn btn-outline-dark btn-lg"
                        data-toggle="modal"
                        data-target="#enquireProductModal"
                        data-product="{{ $product }}"
                        @if ($product->number_of_options >= 1) {
                            data-option="{{ $options->first() }}"
                        @else
                            data-option="{{ $options->last() }}"
                        @endif
                    >
                        Enquire
                    </a>

                    <span class="ml-2 font-weight-bold text-uppercase" style="letter-spacing: 1px;">
                        @if (auth()->user() && $product->existsInWishlist(auth()->user()))
                            <a
                                href="{{ route('users.wishlist.remove', $product->id) }}"
                                class="mainSiteLink-invert btnWishlist"
                                data-verb="Remove"
                            >
                                Remove from Wishlist
                            </a>
                        @elseif (auth()->user() && ! $product->existsInWishlist(auth()->user()))
                            <a
                                href="{{ route('users.wishlist.add', $product->code) }}"
                                class="mainSiteLink-invert btnWishlist"
                                data-verb="Add"
                            >
                                Add To Wishlist
                            </a>
                        @elseif (auth()->guest())
                            <a
                                href="{{ route('users.login') }}?addToWishlist={{ $product->code }}"
                                class="mainSiteLink-invert"
                                title="Please login to add this product in your wishlist"
                                data-toggle="tooltip"
                            >
                                Add To Wishlist
                            </a>
                        @endif
                    </span>

                    <div class="mb-5"></div>

                    @include('products._options_setting')

                    <div class="mb-5"></div>

                    @include('products._detailed_info')
                </h2>
            </div>
        </div>
    </div>

    <div class="mb-5"></div>

    @include('products._related_products')
@endsection

@section('pageScripts')
    <script src="{{ url('/plugins/elevatezoom/jquery.elevatezoom.min.js') }}"></script>
    <script src="{{ url('/plugins/owl-carousel/dist/owl.carousel.min.js') }}"></script>

    <script>
        var imagez = $("#productImage");
        imagez.elevateZoom({
            easing: true,
            zoomType: "inner",
            cursor: "crosshair",
        });

        $('.owl-carousel').owlCarousel({
            loop: false,
            nav: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                    nav: true
                },
                400: {
                    items: 2,
                    nav: true
                },
                600: {
                    items: 3,
                    nav: true
                },
                1000: {
                    items: 5,
                    nav: true,
                }
            }
        });

        @if (auth()->check())
            $('.btnWishlist').on('click', function (e) {
                e.preventDefault();

                var self = $(this)
                    verb = self.data('verb');

                if (verb == 'Add') {
                    var action = 'Adding',
                        text = 'Added',
                        fail = 'Add To Wishlist';
                } else if (verb == 'Remove') {
                    var action = 'Removing'
                        text = 'Removed',
                        fail = 'Remove From Wishlist';
                }

                self.prop('disabled', true)
                       .html('<i class="fas fa-spinner fa-spin"></i> '+ action +'...');

                $.ajax({
                    url: $(this).attr('href'),
                    type: 'GET',
                    success: function (res) {
                        self.prop('disabled', false);

                        displayGrowlNotification(res.status, res.title, res.message, res.delay);

                        if (res.status == 'success') {
                            self.html(text);

                            setTimeout(function () {
                                window.location = res.location;
                            }, res.delay + 1000);
                        } else {
                            self.html(fail);
                        }
                    },
                    error: function (err) {
                        self.prop('disabled', false).html(fail);

                        alert('Something went wrong. Please try again later.');
                    },
                });

                return false;
            });
        @endif

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

        @if ($product->number_of_options == 1)
            $('.optionValue1').change(function (e) {
                var option = $(this).find(':selected').data('optid');

                var sellingPrice = option.selling_price;
                if (option.discount_price > 0) {
                    sellingPrice = option.discount_price;
                }

                $('.optionCode').html(option.option_code);
                $('.amount').html(parseFloat(sellingPrice).toFixed(2));
                $('.inStock').html(option.stock);

                var addToCartLink = '/cart/add/{{ $product->code }}/'+option.option_code;

                $('.btnAddToCart').attr('href', addToCartLink);

                imagez.removeData('elevateZoom');
                imagez.removeData('zoomImage');

                var img = option.image.split('; ');

                $('#productImage').attr('src', img.slice(-1)[0]).attr('data-image-zoom', img.slice(-1)[0]);

                imagez.elevateZoom({
                    easing: true,
                    zoomType: "inner",
                    cursor: "crosshair",
                });
            });
        @endif

        @if ($product->number_of_options == 2)
            $('.optionValue1').change(function (e) {
                var option2Collection = $(this).find(':selected').data('opt2id');

                $('select.optionValue2').prop('disabled', true);

                var options = '<option value="0">Please Select</option>';

                $.each(option2Collection, function (key, opt) {
                    options += '<option value="'+opt.option_code+'" ';
                    options += 'data-option-code="'+opt.option_code+'" ';
                    options += 'data-selling="'+opt.selling_price+'" ';
                    options += 'data-discount="'+opt.discount_price+'" ';
                    options += 'data-image="'+opt.image+'" ';
                    options += 'data-stock="'+opt.stock+'">';
                    options += opt.option_2_value;
                    options += '</option>';
                });

                $('select.optionValue2').html(options);

                $('select.optionValue2').prop('disabled', false);
            });

            $('.optionValue2').change(function (e) {
                var selected = $(this).find(':selected');

                var sellingPrice = selected.data('selling');
                if (selected.data('discount') > 0) {
                    sellingPrice = selected.data('discount');
                }

                $('.optionCode').html(selected.data('option-code'));
                $('.amount').html(parseFloat(sellingPrice).toFixed(2));
                $('.inStock').html(selected.data('stock'));

                var addToCartLink = '/cart/add/{{ $product->code }}/'+selected.data('option-code');

                $('.btnAddToCart').attr('href', addToCartLink);

                imagez.removeData('elevateZoom');
                imagez.removeData('zoomImage');

                var img = selected.data('image').split('; ');

                $('#productImage').attr('src', img.slice(-1)[0]).attr('data-image-zoom', img.slice(-1)[0]);

                imagez.elevateZoom({
                    easing: true,
                    zoomType: "inner",
                    cursor: "crosshair",
                });
            });
        @endif
    </script>
@endsection

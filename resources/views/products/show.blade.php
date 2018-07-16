@php
$options = $product->options()->onlyEnabled()->get();

$option = $options->last();

$price = $option != null ? $option->selling_price : 0.0;
$pricingColumn = 'selling_price';
if ($option && $option->discount_price > 0.0) {
    $price = $option->discount_price;
    $pricingColumn = 'discount_price';
}

$link = route('cart.add', $product->code);
if ($product->number_of_options >= 1) {
    $options = $options->sortBy($pricingColumn);

    $link = route('cart.add', [
        $product->code, $options->last()->option_code
    ]);
}

if ($product->number_of_options == 2) {
    // $options->where('option_1_value', )
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
                    <a href="{{ url($category->pageUrl()) }}" class="mainSiteLink">
                        {{ title_case($category->name) }}
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
                        <span class="optionCode">{{ $product->options->last()->option_code }}</span>
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

                    <a href="{{ $link }}" class="btn btn-dark btn-lg btnAddToCart text-uppercase">
                        Add To Cart
                    </a>

                    <span class="ml-2 font-weight-bold text-uppercase" style="letter-spacing: 1px;">
                        <a href="javascript:void(0)" class="mainSiteLink-invert">Add To Wishlist</a>
                    </span>

                    <div class="mb-5"></div>

                    @include('products._options_setting')

                    <div class="mb-5"></div>

                    @include('products._detailed_info')
                </h2>
            </div>
        </div>
    </div>

    <div class="mb-4"></div>
@endsection

@section('pageScripts')
    <script src="{{ url('/plugins/elevatezoom/jquery.elevatezoom.min.js') }}"></script>

    <script>
        var imagez = $("#productImage");
        imagez.elevateZoom({
            easing: true,
            zoomType: "inner",
            cursor: "crosshair",
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
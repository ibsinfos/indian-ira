@extends('partials._layout')

@section('title_and_meta_info')
    <title>{{ $tag->meta_title }}</title>
    <meta name="description" content="{{ $tag->meta_description }}" />
    <meta name="keywords" content="{{ $tag->meta_keywords }}" />
@endsection

@section('content')
    <div class="container mt-4">
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
                <li class="breadcrumb-item active" aria-current="page">
                    {{ title_case($tag->name) }}
                </li>
            </ol>
        </nav>

        <h1 class="display-6 mb-4" style="font-size: 1.25rem;">
            Products tagged as: <span class="font-weight-bold">{{ title_case($tag->name) }}</span>
        </h1>

        <div class="row">
            @if ($products->isNotEmpty())
                @foreach ($products as $product)
                    @php
                    $options = collect();
                    $price = 0.0;

                    if ($product->number_of_options >= 1) {
                        $options = $product->options()->onlyEnabled()->get()->sortBy('sort_number');
                    }
                    @endphp

                    <div class="col-md-3">
                        <div class="card mb-3" style="border: 1px solid #ddd;">
                            <img
                                src="{{ url($product->catalogueImage()) }}"
                                alt="{{ $product->name }}"
                                class="mb-4 w-100"
                            >

                            <div class="p-2">
                                <div class="productName mb-3">
                                    <a href="{{ url($tag->productPageUrl($product)) }}" class="mainSiteLink">
                                        @if(strlen($product->name) > 40)
                                            {{ substr(title_case($product->name), 0, 40) . '...' }}
                                        @else
                                            {{ title_case($product->name) }}
                                        @endif
                                    </a>
                                </div>

                                <div class="productPrice mb-3">
                                    <i class="fas fa-rupee-sign"></i>
                                    @php
                                    $option = $options->last();

                                    if ($option && $option->discount_price > 0.0) {
                                        $price = $option->discount_price;
                                    } elseif ($option && $option->discount_price <= 0.0) {
                                        $price = $option->selling_price;
                                    }
                                    @endphp
                                    {{ number_format($price, 2) }}
                                </div>

                                @php
                                $link = route('cart.add', $product->code);
                                if ($product->number_of_options >= 1) {
                                    $link = route('cart.add', [
                                        $product->code, $options->first()->option_code
                                    ]);
                                }
                                @endphp

                                <a href="{{ $link }}" class="btn btn-dark btn-sm btnAddToCart">
                                    Add To Cart
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-danger ml-3">
                    No Products found in this tag.
                </p>
            @endif
        </div>

        <div class="mb-4"></div>

        {{ $products->links() }}
    </div>

    <div class="mb-4"></div>
@endsection

@section('pageScripts')
    <script>
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

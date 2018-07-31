@extends('partials._layout')

@section('title_and_meta_info')
    <title>{{ $tag->meta_title }}</title>
    <meta name="description" content="{{ $tag->meta_description }}" />
    <meta name="keywords" content="{{ $tag->meta_keywords }}" />
@endsection

@section('pageStyles')
    <style>
        @media screen and (max-width: 380px) {
            .col-6 {
                max-width: 100% !important;
                flex: 0 0 100% !important;
            }
        }
    </style>
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

        <h1 class="display-6 mb-4 mt-md-0 mt-sm-4" style="font-size: 1.25rem;">
            Products tagged as: <span class="font-weight-bold">{{ title_case($tag->name) }}</span>
        </h1>

        <div class="row">
            @if ($products->isNotEmpty())
                @foreach ($products as $product)
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                        @include('partials._product_card', ['tag' => $tag])
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

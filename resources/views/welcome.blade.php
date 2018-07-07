@extends('partials._layout')

@section('title_and_meta_info')
    <title>{{ config('app.name') }}</title>
    <meta name="description" content="{{ config('app.name') }}" />
    <meta name="keywords" content="{{ config('app.name') }}" />
@endsection

@section('pageStyles')
    <link rel="stylesheet" href="{{ url('/plugins/slippry/dist/slippry.css') }}">
    <link rel="stylesheet" href="{{ url('/plugins/owl-carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ url('/plugins/owl-carousel/dist/assets/owl.theme.default.min.css') }}">

    <style>
        .owl-carousel .item {
            border: 1px solid #000;
            border-radius: 3px;
        }

        .productName {
            min-height: 100px;
        }
    </style>
@endsection

@section('content')
    @include('partials._slider')

    @include('partials._carousels')
@endsection

@section('pageScripts')
    <script src="{{ url('/plugins/slippry/dist/slippry.min.js') }}"></script>
    <script src="{{ url('/plugins/owl-carousel/dist/owl.carousel.min.js') }}"></script>

    <script>
        $('#slipprySlider').slippry({
            captions: false,
            transition: 'horizontal',
            pager: false
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

        $('body').on('click', '.btnAddToCart', function (e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('href'),
                type: 'GET',
                success: function (res) {
                    displayGrowlNotification(res.status, res.title, res.message, res.delay);
                },
                error: function (err) {
                    console.log(err);
                },
            });

            return false;
        });
    </script>
@endsection

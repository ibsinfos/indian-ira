@extends('partials._layout')

@section('title_and_meta_info')
    <title>{{ config('app.name') }}</title>
    <meta name="description" content="{{ config('app.name') }}" />
    <meta name="keywords" content="{{ config('app.name') }}" />
@endsection

@section('pageStyles')
    <link rel="stylesheet" href="{{ url('/plugins/slippry/dist/slippry.css') }}">
@endsection

@section('content')
    <ul id="slipprySlider">
        <li>
            <a href="#slide1">
                <img src="http://placehold.it/1920x400" alt="Image Banner - 1">
            </a>
        </li>
        <li>
            <a href="#slide2">
                <img src="http://placehold.it/1920x400"  alt="Image Banner - 2">
            </a>
        </li>
        <li>
            <a href="#slide3">
                <img src="http://placehold.it/1920x400" alt="Image Banner - 3">
            </a>
        </li>
    </ul>
@endsection

@section('pageScripts')
    <script src="{{ url('/plugins/slippry/dist/slippry.min.js') }}"></script>

    <script>
        $('#slipprySlider').slippry({
            captions: false,
            transition: 'horizontal',
            pager: false
        });
    </script>
@endsection

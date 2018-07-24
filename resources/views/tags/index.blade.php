@extends('partials._layout')

@section('title_and_meta_info')
    <title>{{ config('app.name') }} | All Tags</title>
    <meta name="description" content="{{ config('app.name') }} All Tags" />
    <meta name="keywords" content="{{ config('app.name') }} All Tags" />
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
                    Tags
                </li>
            </ol>
        </nav>

        <h1 class="display-6 font-weight-bold mb-4" style="font-size: 1.25rem;">
            List of All Tags
        </h1>

        @foreach ($tags as $tag)
            <a
                href="{{ url($tag->pageUrl()) }}"
                class="btn btn-outline-danger mb-3"
                data-toggle="tooltip"
                title="{{ $tag->short_description }}"
            >{{ title_case($tag->name) }} x {{ $tag->products->where('display', 'Enabled')->count() }}</a>
        @endforeach
    </div>

    <div class="mb-4"></div>
@endsection

@extends('partials._layout')

@section('title_and_meta_info')
    <title>{{ config('app.name') }}</title>
    <meta name="description" content="{{ config('app.name') }}" />
    <meta name="keywords" content="{{ config('app.name') }}" />
@endsection

@section('content')
<h1 class="display-6">
    {{ config('app.name') }}
</h1>
@endsection

@extends('users.partials._layout')

@section('title_and_meta_info')
    <title>{{ $user->getFullName() }} Dashboard</title>
@endsection

@section('content')
    <div class="container">
        <div class="page-header">
            <h2 class="no-padding-margin bold">
                Welcome {{ $user->getFullName() }}
            </h2>
        </div>
    </div>
@endsection

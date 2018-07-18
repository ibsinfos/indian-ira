@extends('users.partials._layout')

@section('title_and_meta_info')
    <title>Order Details: {{ $orders->first()->order_code }}</title>
@endsection

@section('content')
<div class="container-fluid">
    <nav class="mb-4" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('users.dashboard') }}" class="mainSiteLink">
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('users.orders') }}" class="mainSiteLink">
                    Orders
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $orders->first()->order_code }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="mb-xl-5 col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="header p-0 m-0">
                        Viewing History Details: {{ $orders->first()->order_code }}
                    </h3>
                </div>

                <div class="card-body">
                    <div class="mb-4 mr-3 float-none float-md-right">
                        <a
                            href="{{ route('users.orders', $orders->first()->order_code) }}"
                            class="btn btn-outline-dark btn-sm"
                        >
                            All Orders
                        </a>
                        <a
                            href="{{ route('users.orders.products', $orders->first()->order_code) }}"
                            class="btn btn-outline-dark btn-sm"
                        >
                            Products Details
                        </a>
                        <a
                            href="{{ route('users.orders.address', $orders->first()->order_code) }}"
                            class="btn btn-outline-dark btn-sm"
                        >
                            Address Details
                        </a>
                        <a
                            href="{{ route('users.dashboard') }}"
                            class="btn btn-outline-dark text-black btn-sm shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                        >
                            Go to Dashboard
                        </a>
                    </div>

                    <div class="table-responsive">
                        @include('users.orders._history_table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageScripts')
    <script>
        var table = $('.historyTable').dataTable({
            "aaSorting": [],
            "order": [[0, "asc"]]
        });
    </script>
@endsection

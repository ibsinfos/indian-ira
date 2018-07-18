@extends('users.partials._layout')

@section('title_and_meta_info')
    <title>List of All Orders</title>
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
            <li class="breadcrumb-item active" aria-current="page">Orders</li>
        </ol>
    </nav>

    <div class="row">
        <div class="mb-xl-5 col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="header p-0 m-0">
                        List of All Orders (Total: {{ $allOrders->count() }})

                        <div class="float-right">
                            <a
                                href="{{ route('users.dashboard') }}"
                                class="btn btn-outline-light text-black btn-sm font-weight-bold shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                            >Go to Dashboard</a>
                        </div>
                    </h3>
                </div>

                <div class="card-body">
                    <p class="text-danger text-justify mb-5" style="font-size: 14px;">
                        You can click on the column header if you want to sort that column either in Ascending order or in Descending order.<br />Default: Descending Order.
                    </p>

                    <div class="table-responsive">
                        @include('users.orders.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mb-5"></div>
@endsection

@section('pageScripts')
    <script>
        var table = $('.allOrdersTable').dataTable({
            "aaSorting": [],
            "order": [[0, "asc"]]
        });
    </script>
@endsection

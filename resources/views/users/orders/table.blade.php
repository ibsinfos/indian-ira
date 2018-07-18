<table class="table table-bordered table-striped allOrdersTable" data-page-length="100">
    <thead>
        <th>Sr. No.</th>
        <th>Order Code</th>
        <th>Order Payable Amount</th>
        <th>Payment Method</th>
        <th>Order Placed On</th>
        <th>Action</th>
    </thead>

    <tbody>
        @php $i = 1; @endphp
        @foreach ($allOrders as $orders)
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $order->order_code }}</td>
                    <td>
                        <i class="fas fa-rupee-sign"></i>
                        {{ number_format($order->cart_total_payable_amount, 2) }}
                    </td>
                    <td>{{ title_case($order->payment_method) }}</td>
                    <td>{{ $order->formatsCreatedAt() }}</td>
                    <td>
                        <a
                            href="{{ route('users.orders.products', $order->order_code) }}"
                            class="btn btn-sm btn-info mb-sm-2 mb-md-2"
                        >
                            <i class="fas fa-eye"></i>
                            View
                        </a>
                    </td>
                </tr>
            @endforeach

            @php $i++; @endphp
        @endforeach
    </tbody>
</table>

<table class="table table-bordered table-striped allOrdersTable" data-page-length="100">
    <thead>
        <th>Sr. No.</th>
        <th>Order Code</th>
        <th>Ordered By</th>
        <th>Order Payable Amount</th>
        <th>Payment Method</th>
        <th>Order Placed On</th>
        <th>Action</th>
    </thead>

    <tbody>
        @php $i = 1; @endphp
        @foreach ($allOrders as $orders)
            @foreach ($orders as  $order)
                <tr @if ($order->deleted_at != null) class="deletedRow" @endif>
                    <td>{{ $i }}</td>
                    <td>{{ $order->order_code }}</td>
                    <td>
                        {{ $order->user_full_name }}<br />
                        {{ $order->user_username }}<br />
                        {{ $order->user_email }}<br />
                        {{ $order->user_contact_number }}
                    </td>
                    <td>
                        <i class="fas fa-rupee-sign"></i>
                        {{ number_format($order->cart_total_payable_amount, 2) }}
                    </td>
                    <td>{{ title_case($order->payment_method) }}</td>
                    <td>{{ $order->formatsCreatedAt() }}</td>
                    <td>
                        @if ($order->deleted_at != null)
                            <a
                                href="{{ route('admin.orders.restore', $order->order_code) }}"
                                class="btn btn-sm btn-success text-white ajaxBtnOnTable mb-sm-2 mb-md-2"
                                title="Restore this order details"
                                data-toggle="tooltip"
                                data-placement="top"
                            >
                                <i class="fas fa-sync-alt"></i>
                                Restore
                            </a>

                            <a
                                href="{{ route('admin.orders.destroy', $order->order_code) }}"
                                class="btn btn-sm btn-outline-warning text-white ajaxBtnOnTable"
                                title="Permanently delete this order details"
                                data-toggle="tooltip"
                                data-placement="top"
                            >
                                <i class="fas fa-trash"></i>
                                Destroy
                            </a>
                        @else
                            <a
                                href="{{ route('admin.orders.showProducts', $order->order_code) }}"
                                class="btn btn-sm btn-info mb-sm-2 mb-md-2"
                            >
                                <i class="fas fa-eye"></i>
                                View
                            </a>

                            <a
                                href="{{ route('admin.orders.delete', $order->order_code) }}"
                                class="btn btn-outline-warning btn-sm ajaxBtnOnTable"
                                title="Temporarily delete this order details"
                                data-toggle="tooltip"
                                data-placement="top"
                            >
                                <i class="fas fa-trash"></i>
                                Delete
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach

            @php $i++; @endphp
        @endforeach
    </tbody>
</table>

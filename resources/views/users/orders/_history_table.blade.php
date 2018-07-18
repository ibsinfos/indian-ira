<table class="table table-bordered historyTable" style="font-size: 13px;" data-page-length="100">
    <thead>
        <th>Sr. No.</th>
        <th>Product Details</th>
        <th>Status</th>
        <th>Shipping Details</th>
        <th>Notes</th>
        <th>Added On</th>
    </thead>

    <tbody>
        @foreach ($history->sortByDesc('id') as $key => $data)
            <tr>
                <td>{{ ++$key }}</td>
                <td>
                    <img
                        src="{{ url('/images/no-image.jpg') }}"
                        alt="{{ $data->order->product_name }}"
                        class="img-fluid float-left mr-3"
                        style="width: 30%"
                    />

                    <div class="mb-2">
                        <a
                            href="{{ $data->order->product->pageUrl() }}"
                            class="mainSiteLink font-weight-bold"
                            style="font-size: 15px;"
                        >
                            {{ title_case($data->order->product_name) }}
                        </a>
                    </div>

                    <div class="mb-2">
                        {{ $data->order->product_code }}
                        @if ($data->order->product_number_of_options >= 1)
                            / {{ $data->order->product_option_code }}
                        @endif
                    </div>
                </td>
                <td>{{ title_case($data->status) }}</td>
                <td>
                    Company: {{ title_case($data->shipping_company) }}<br />
                    URL: {{ $data->shipping_tracking_url }}<br />
                </td>
                <td>{{ $data->notes }}</td>
                <td>{{ $data->formatsCreatedAt() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

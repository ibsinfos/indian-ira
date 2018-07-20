<table class="table table-striped table-bordered allProductPricesAndStockTable" data-page-length="100">
    <thead>
        <th>Sr. No.</th>
        <th>Product Id</th>
        <th>Product Details</th>
        <th>Stock</th>
        <th>GST Percent</th>
        <th>Selling Price</th>
        <th>Discount Price</th>
        <th>Action</th>
    </thead>

    <tbody>
        @foreach ($products as $index => $row)
            @php
            $product = $row->product;
            @endphp

            <tr>
                <td>{{ ++$index }}</td>
                <td>{{ $row->product_id }}</td>
                <td>
                    @if ($row->hasUploadedImageFile())
                        <img
                            src="{{ url($row->cartImage()) }}"
                            alt="{{ title_case($product->name) }}"
                            class="img-fluid float-left mr-3"
                        >
                    @elseif($product->hasUploadedImageFile())
                        <img
                            src="{{ url($product->cartImage()) }}"
                            alt="{{ title_case($product->name) }}"
                            class="img-fluid float-left mr-3"
                        >
                    @else
                        <img
                            src="{{ url('/images/no-image.jpg') }}"
                            alt="Image Not Found"
                            class="img-fluid float-left mr-3"
                        />
                    @endif

                    <a href="{{ $product->canonicalPageUrl() }}" target="_blank" class="mainSiteLink font-weight-bold">
                        {{ title_case($product->name) }}
                    </a><br /><br />
                    {{ $product->code }} / {{ $row->option_code }}
                </td>
                <td>{{ $row->stock }}</td>
                <td>{{ $product->gst_percent }}%</td>
                <td><i class="fas fa-rupee-sign"></i> {{ number_format($row->selling_price, 2) }}</td>
                <td><i class="fas fa-rupee-sign"></i> {{ number_format($row->discount_price, 2) }}</td>
                <td>
                    <a
                        href="#"
                        class="btn btn-sm btn-info"
                        data-toggle="modal"
                        data-target="#editPriceAndStockModal"
                        data-option="{{ $row }}"
                    >
                        <i class="fas fa-pencil-alt"></i> Edit
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

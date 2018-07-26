<table class="table table-striped table-bordered productInWishlistTable" data-page-lengt="100">
    <thead>
        <th>Sr. No</th>
        <th>Product Details</th>
        <th>Added On</th>
        <th>Action</th>
    </thead>

    <tbody>
        @foreach ($productsInWishlst as $key => $row)
            <tr>
                <td>{{ ++$key }}</td>
                <td>
                    <img
                        src="{{ url($row->product_image) }}"
                        alt="{{ title_case($row->product_name) }}"
                        class="img-fluid float-left mr-3"
                    />

                    <div class="mb-2">
                        <a
                            href="{{ $row->product_page_url }}"
                            class="mainSiteLink font-weight-bold"
                            style="font-size: 15px;"
                        >
                            {{ title_case($row->product_name) }}
                        </a>
                    </div>

                    {{ $row->product_code }}
                </td>
                <td>{{ $row->formatsCreatedAt() }}</td>
                <td>
                    @php
                    $product = $row->product;
                    $link = route('cart.add', $row->product_code);

                    $options = $product->options()->onlyEnabled()->get()->sortBy('sort_number');

                    $option = $options->last();

                    if ($product->number_of_options >= 1) {
                        $option = $options->first();

                        $link = route('cart.add', [
                            $row->product_code, $option->option_code
                        ]);
                    }
                    @endphp

                    @if ($option->stock >= 1)
                        <a
                            href="{{ $link }}"
                            class="btn btn-dark btn-sm btnAddToCart text-uppercase mb-1"
                        >
                            Add To Cart
                        </a>
                    @else
                        <a
                            href="javascript:void(0)"
                            class="btn btn-dark btn-sm btnAddToCart text-uppercase mb-1"
                        >
                            Out of Stock
                        </a>
                    @endif

                    <a
                        href="{{ url($row->product_page_url) }}"
                        class="btn btn-sm btn-info"
                        target="_blank"
                    >
                        <i class="fas fa-eye"></i>
                        View
                    </a>

                    <a
                        href="{{ route('users.wishlist.remove', $row->product_id) }}"
                        class="btn btn-sm btn-outline-danger ajaxBtnOnTable"
                        title="Permanently remove this product from your wishlist"
                        data-toggle="tooltip"
                    >
                        <i class="fas fa-trash"></i>
                        Delete
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

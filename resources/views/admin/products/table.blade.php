<table class="table table-striped table-bordered allProductsTable" data-page-length="100">
    <thead>
        <th>Sr. No</th>
        <th>Product Details</th>
        <th>Categories</th>
        <th>Options Count</th>
        <th>Display</th>
        <th>Added On</th>
        <th>Action</th>
    </thead>

    <tbody>
        @forelse($products as $key => $product)
            <tr @if ($product->deleted_at != null) class="deletedRow" @endif>
                <td>{{ ++$key }}</td>
                <td>
                    <img
                        src="{{ url($product->cartImage()) }}"
                        alt="{{ $product->name }}"
                        class="float-left mr-3 w-25"
                    />

                    <a href="{{ $product->canonicalPageUrl() }}" target="_blank" class="mainSiteLink">
                        {{ title_case($product->name) }}
                    </a><br />
                    {{ $product->code }}
                </td>
                <td>
                    {{ title_case($product->categories->implode('name', '; ')) }}
                </td>
                <td>{{ $product->number_of_options . ' ' . str_plural('Option', $product->number_of_options) }}</td>
                <td>{{ $product->display }}</td>
                <td>{{ $product->formatsCreatedAt() }}</td>
                <td>
                    @if ($product->deleted_at != null)
                        <a
                            href="{{ route('admin.products.restore', $product->id) }}"
                            class="btn btn-sm btn-success text-white ajaxBtnOnTable"
                            title="Restore this product details"
                            data-toggle="tooltip"
                            data-placement="top"
                        >
                            <i class="fas fa-sync-alt"></i>
                            Restore
                        </a>

                        <a
                            href="{{ route('admin.products.destroy', $product->id) }}"
                            class="btn btn-sm btn-outline-warning text-white ajaxBtnOnTable"
                            title="Permanently delete this product details"
                            data-toggle="tooltip"
                            data-placement="top"
                        >
                            <i class="fas fa-trash"></i>
                            Destroy
                        </a>
                    @else
                        <a
                            href="{{ route('admin.products.priceAndOptions', $product->id) }}"
                            class="btn btn-sm btn-info mb-sm-2 mb-md-2 mb-lg-0"
                        >
                            <i class="fas fa-rupee-sign"></i>
                            Prices and Options
                        </a>

                        <hr>

                        <a
                            href="{{ route('admin.products.edit', $product->id) }}?general"
                            class="btn btn-sm btn-info mb-sm-2 mb-md-2 mb-lg-0"
                        >
                            <i class="fas fa-pencil-alt"></i>
                            Edit
                        </a>

                        <a
                            href="{{ route('admin.products.delete', $product->id) }}"
                            class="btn btn-outline-warning btn-sm ajaxBtnOnTable"
                            title="Temporarily delete this product details"
                            data-toggle="tooltip"
                            data-placement="top"
                        >
                            <i class="fas fa-trash"></i>
                            Delete
                        </a>
                    @endif
                </td>
            </tr>
        @empty
        @endforelse
    </tbody>
</table>

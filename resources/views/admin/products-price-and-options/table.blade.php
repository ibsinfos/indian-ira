<table class="table table-striped table-bordered allProductPricesAndOptionsTable" data-page-length="100">
    <thead>
        <th>Sr. No</th>
        <th>Option Code</th>
        <th>Display</th>
        <th>Added On</th>
        <th>Action</th>
    </thead>

    <tbody>
        @forelse($pricesAndOptions as $key => $option)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $option->option_code }}</td>
                <td>{{ $option->display }}</td>
                <td>{{ $option->formatsCreatedAt() }}</td>
                <td>
                    <a
                        href="#"
                        data-toggle="modal"
                        data-target="#editPriceAndOptionModal"
                        data-id="{{ $option->id }}"
                        data-option="{{ $option }}"
                        data-cartimg="{{ url($option->cartImage()) }}"
                        class="btn btn-sm btn-info mb-sm-2 mb-md-2 mb-lg-0"
                    >
                        <i class="fas fa-pencil-alt"></i>
                        Edit
                    </a>

                    <a
                        href="{{ route('admin.products.priceAndOptions.destroy', [$option->product_id, $option->id]) }}"
                        class="btn btn-outline-danger btn-sm ajaxBtnOnTable"
                        title="Permanently destroy this price and option details"
                        data-toggle="tooltip"
                        data-placement="top"
                    >
                        <i class="fas fa-trash"></i>
                        Destroy
                    </a>
                </td>
            </tr>
        @empty
        @endforelse
    </tbody>
</table>

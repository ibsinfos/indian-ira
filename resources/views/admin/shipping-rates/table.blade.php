<table class="table table-striped table-bordered allShippingRatesTable" data-page-length="100">
    <thead>
        <th>Sr. No</th>
        <th>Company Details</th>
        <th>Weight Details</th>
        <th>Amount</th>
        <th>Action</th>
    </thead>

    <tbody>
        @forelse($shippingRates as $key => $shippingRate)
            <tr @if ($shippingRate->deleted_at != null) class="deletedRow" @endif>
                <td>{{ ++$key }}</td>
                <td>
                    <span class="font-weight-bold">Name:</span><br />
                    {{ $shippingRate->shipping_company_name }}<br /><br />

                    <span class="font-weight-bold">Tracking URL:</span><br />
                    {{ $shippingRate->shipping_company_tracking_url }}<br />
                </td>
                <td>
                    <span class="font-weight-bold">From:</span><br />
                    {{ $shippingRate->weight_from }} {{ str_plural('gram', $shippingRate->weight_from) }}<br /><br />

                    <span class="font-weight-bold">To:</span><br />
                    {{ $shippingRate->weight_to }} {{ str_plural('gram', $shippingRate->weight_to) }}<br />
                </td>
                <td>
                    <i class="fas fa-rupee-sign"></i>
                    {{ number_format($shippingRate->amount, 2) }}
                </td>
                <td>
                    @if ($shippingRate->deleted_at != null)
                        <a
                            href="{{ route('admin.shippingRates.restore', $shippingRate->id) }}"
                            class="btn btn-sm btn-success text-white ajaxBtnOnTable"
                            title="Restore this shipping details"
                            data-toggle="tooltip"
                            data-placement="top"
                        >
                            <i class="fas fa-sync-alt"></i>
                            Restore
                        </a>

                        <a
                            href="{{ route('admin.shippingRates.destroy', $shippingRate->id) }}"
                            class="btn btn-sm btn-outline-warning text-white ajaxBtnOnTable"
                            title="Permanently delete this shipping details"
                            data-toggle="tooltip"
                            data-placement="top"
                        >
                            <i class="fas fa-trash"></i>
                            Destroy
                        </a>
                    @else
                        <a
                            href="#"
                            class="btn btn-sm btn-info mb-sm-2 mb-md-2 mb-lg-0"
                            title="Edit this shipping details"
                            data-toggle="modal"
                            data-target="#editShippingRateModal"
                            data-id="{{ $shippingRate->id }}"
                            data-shipping="{{ $shippingRate }}"
                        >
                            <i class="fas fa-pencil-alt"></i>
                            Edit
                        </a>

                        <a
                            href="{{ route('admin.shippingRates.delete', $shippingRate->id) }}"
                            class="btn btn-outline-warning btn-sm ajaxBtnOnTable"
                            title="Temporarily delete this shipping details"
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

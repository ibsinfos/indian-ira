<table class="table table-striped table-bordered allCouponsTable" data-page-length="100">
    <thead>
        <th>Sr. No</th>
        <th>Coupon Code</th>
        <th>Coupon Discount Percent</th>
        <th>Added On</th>
        <th>Action</th>
    </thead>

    <tbody>
        @forelse($coupons as $key => $coupon)
            <tr @if ($coupon->deleted_at != null) class="deletedRow" @endif>
                <td>{{ ++$key }}</td>
                <td>{{ $coupon->code }}</td>
                <td>{{ $coupon->discount_percent }}%</td>
                <td>{{ $coupon->formatsCreatedAt() }}</td>
                <td>
                    @if ($coupon->deleted_at != null)
                        <a
                            href="{{ route('admin.coupons.restore', $coupon->id) }}"
                            class="btn btn-sm btn-success text-white ajaxBtnOnTable"
                            title="Restore this coupon details"
                            data-toggle="tooltip"
                            data-placement="top"
                        >
                            <i class="fas fa-sync-alt"></i>
                            Restore
                        </a>

                        <a
                            href="{{ route('admin.coupons.destroy', $coupon->id) }}"
                            class="btn btn-sm btn-outline-warning text-white ajaxBtnOnTable"
                            title="Permanently delete this coupon details"
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
                            title="Edit this coupon details"
                            data-toggle="modal"
                            data-target="#editCouponModal"
                            data-id="{{ $coupon->id }}"
                            data-coupon="{{ $coupon }}"
                        >
                            <i class="fas fa-pencil-alt"></i>
                            Edit
                        </a>

                        <a
                            href="{{ route('admin.coupons.delete', $coupon->id) }}"
                            class="btn btn-outline-warning btn-sm ajaxBtnOnTable"
                            title="Temporarily delete this coupon details"
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

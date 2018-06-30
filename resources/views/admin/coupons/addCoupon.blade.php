<div
    class="modal fade"
    id="addCouponModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Coupon</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form
                    action="{{ route('admin.coupons.store') }}"
                    method="POST"
                    id="formAddCoupon"
                    autocomplete="notWanted"
                >
                    @csrf

                    <div class="form-group">
                        <label class="normal" for="code">Coupon Code:</label>
                        <input
                            type="text"
                            name="code"
                            id="code"
                            value="{{ old('code') }}"
                            class="form-control hasMaxLength"
                            placeholder="Eg. CP2018"
                            required="required"
                            autocomplete="sc-notWanted"
                            maxlength="30"
                            data-toggle="tooltip"
                            title="It should contain only alphabets, numbers, dashes (-) and underscores ( _ )"
                        />
                    </div>

                    <div class="form-group">
                        <label class="normal" for="discount_percent">Coupon Discount Percent:</label>
                        <input
                            type="text"
                            name="discount_percent"
                            id="discount_percent"
                            value="{{ old('discount_percent') }}"
                            class="form-control hasMaxLength"
                            placeholder="Eg. 10"
                            required="required"
                            autocomplete="sc-notWanted"
                            maxlength="5"
                            data-toggle="tooltip"
                            title="It should contain only numbers with optional decimal upto 2 precisions."
                        />
                    </div>

                    <div class="errorsInAddingCoupon"></div>

                    <button class="btn submitButton btnAddCoupon mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

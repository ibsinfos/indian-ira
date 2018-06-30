<div
    class="modal fade"
    id="editCouponModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form
                    method="POST"
                    id="formEditCoupon"
                    autocomplete="notWanted"
                >
                    @csrf

                    <div class="form-group">
                        <label class="normal" for="code">Coupon Code:</label>
                        <input
                            type="text"
                            name="code"
                            id="code"
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
                            class="form-control hasMaxLength"
                            placeholder="Eg. CP2018"
                            required="required"
                            autocomplete="sc-notWanted"
                            maxlength="5"
                            data-toggle="tooltip"
                            title="It should contain only numbers with optional decimal upto 2 precisions."
                        />
                    </div>

                    <div class="errorsInEditingCoupon"></div>

                    <button class="btn submitButton btnEditCoupon mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

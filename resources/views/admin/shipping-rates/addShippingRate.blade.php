<div
    class="modal fade"
    id="addShippingRateModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Shipping Rate</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form
                    action="{{ route('admin.shippingRates.store') }}"
                    method="POST"
                    id="formAddShippingRate"
                    autocomplete="form-ac-off"
                >
                    @csrf

                    <div class="form-group">
                        <label class="normal" for="shipping_company_name">Shipping Company Name:</label>
                        <input
                            type="text"
                            name="shipping_company_name"
                            id="shipping_company_name"
                            value="{{ old('shipping_company_name') }}"
                            class="form-control hasMaxLength"
                            placeholder="Eg. My Logistics Co"
                            required="required"
                            autocomplete="sc-notWanted"
                            maxlength="250"
                        />
                    </div>

                    <div class="form-group">
                        <label class="normal" for="shipping_company_tracking_url">Shipping Company Tracking URL:</label>
                        <input
                            type="text"
                            name="shipping_company_tracking_url"
                            id="shipping_company_tracking_url"
                            value="{{ old('shipping_company_tracking_url') }}"
                            class="form-control hasMaxLength"
                            placeholder="Eg. http://mysite.com/tracking-url"
                            required="required"
                            autocomplete="sc-notWanted"
                            maxlength="250"
                        />
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <div class="form-group">
                                <label class="normal" for="weight_from">Weight From (in grams):</label>
                                <input
                                    type="text"
                                    name="weight_from"
                                    id="weight_from"
                                    value="{{ old('weight_from') }}"
                                    class="form-control"
                                    placeholder="Eg. 50"
                                    required="required"
                                    autocomplete="sc-notWanted"
                                    data-toggle="tooltip"
                                    title="Weight From should contain only numbers with optional decimal upto 2 precisions only."
                                />
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <div class="form-group">
                                <label class="normal" for="weight_to">Weight To (in grams):</label>
                                <input
                                    type="text"
                                    name="weight_to"
                                    id="weight_to"
                                    value="{{ old('weight_to') }}"
                                    class="form-control"
                                    placeholder="Eg. 100"
                                    required="required"
                                    autocomplete="sc-notWanted"
                                    data-toggle="tooltip"
                                    title="Weight To should contain only numbers with optional decimal upto 2 precisions only."
                                />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="normal" for="amount">Amount:</label>
                        <input
                            type="text"
                            name="amount"
                            id="amount"
                            value="{{ old('amount') }}"
                            class="form-control"
                            placeholder="Eg. 100.00"
                            required="required"
                            autocomplete="sc-notWanted"
                            data-toggle="tooltip"
                            title="Amount should contain only numbers with optional decimal upto 2 precisions only."
                        />
                    </div>

                    <div class="errorsInAddingShippingRate"></div>

                    <button class="btn submitButton btnAddShippingRate mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

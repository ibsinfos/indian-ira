<div class="card">
    <div class="card-header">
        <h2 class="header p-0 m-0" style="font-size: 22px;">
            Shipping Address
        </h2>
    </div>

    <div class="card-body" style="border: 1px solid #ddd">
        <div class="shippingAddress mt-2">
            <div class="form-group">
                <input
                    type="checkbox"
                    name="sameAsBillingAddress"
                    id="sameAsBillingAddress"
                    value="yes"
                    checked="checked"
                >
                Shipping Address Same as Billing Address
            </div>

            <div class="form-group">
                <label class="normal" for="shipping_full_name">Full Name:</label>
                <input
                    type="text"
                    name="shipping_full_name"
                    id="shipping_full_name"
                    value="{{ $user->getFullName() }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. Hojn Doe"
                    required="required"
                    autocomplete="nope"
                    maxlength="200"
                />
            </div>

            <div class="form-group">
                <label class="normal" for="shipping_address_line_1">Address Line 1:</label>
                <input
                    type="text"
                    name="shipping_address_line_1"
                    id="shipping_address_line_1"
                    value="{{ $billingAddress != null ? $billingAddress->address_line_1 : '' }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. Flat No., Building Name"
                    required="required"
                    autocomplete="nope"
                    maxlength="100"
                />
            </div>

            <div class="form-group">
                <label class="normal" for="shipping_address_line_2">Address Line 2: (Optional)</label>
                <input
                    type="text"
                    name="shipping_address_line_2"
                    id="shipping_address_line_2"
                    value="{{ $billingAddress != null ? $billingAddress->address_line_2 : '' }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. Street Name"
                    autocomplete="nope"
                    maxlength="100"
                />
            </div>

            <div class="form-group">
                <label class="normal" for="shipping_area">Area:</label>
                <input
                    type="text"
                    name="shipping_area"
                    id="shipping_area"
                    value="{{ $billingAddress != null ? $billingAddress->area : '' }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. Station Name"
                    required="required"
                    autocomplete="nope"
                    maxlength="100"
                />
            </div>

            <div class="form-group">
                <label class="normal" for="shipping_landmark">Landmark (Optional):</label>
                <input
                    type="text"
                    name="shipping_landmark"
                    id="shipping_landmark"
                    value="{{ $billingAddress != null ? $billingAddress->landmark : '' }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. Some Famous Restaurant"
                    autocomplete="nope"
                    maxlength="100"
                />
            </div>

            <div class="form-group">
                <label class="normal" for="shipping_city">City:</label>
                <input
                    type="text"
                    name="shipping_city"
                    id="shipping_city"
                    value="{{ $billingAddress != null ? $billingAddress->city : '' }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. Mumbai"
                    required="required"
                    autocomplete="nope"
                    maxlength="100"
                    @if (session('shippingRateRecord') && session('shippingRateRecord')->location_type == 'City')
                        readonly="readonly"
                        value="{{ session('shippingRateRecord')->location_name }}"
                    @else
                        value="{{ $billingAddress != null ? $billingAddress->city : '' }}"
                    @endif
                />
            </div>

            <div class="form-group">
                <label class="normal" for="shipping_pin_code">Pin / Zip Code:</label>
                <input
                    type="text"
                    name="shipping_pin_code"
                    id="shipping_pin_code"
                    value="{{ $billingAddress != null ? $billingAddress->pin_code : '' }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. 400034"
                    required="required"
                    autocomplete="nope"
                    maxlength="100"
                />
            </div>

            <div class="form-group">
                <label class="normal" for="shipping_state">State:</label>
                <input
                    type="text"
                    name="shipping_state"
                    id="shipping_state"
                    value="{{ $billingAddress != null ? $billingAddress->state : '' }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. Maharashtra"
                    required="required"
                    autocomplete="nope"
                    maxlength="100"
                    @if (session('shippingRateRecord') && session('shippingRateRecord')->location_type == 'State')
                        readonly="readonly"
                        value="{{ session('shippingRateRecord')->location_name }}"
                    @else
                        value="{{ $billingAddress != null ? $billingAddress->state : '' }}"
                    @endif
                />
            </div>

            <div class="form-group">
                <label class="normal" for="shipping_country">Country:</label>
                <input
                    type="text"
                    name="shipping_country"
                    id="shipping_country"
                    class="form-control hasMaxLength"
                    placeholder="Eg. India"
                    required="required"
                    autocomplete="nope"
                    maxlength="100"
                    @if (session('shippingRateRecord') && session('shippingRateRecord')->location_type == 'Country')
                        readonly="readonly"
                        value="{{ session('shippingRateRecord')->location_name }}"
                    @else
                        value="{{ $billingAddress != null ? $billingAddress->country : '' }}"
                    @endif
                />
            </div>

            <div class="form-group">
                <label class="normal" for="shipping_contact_number">Contact Number:</label>
                <input
                    type="text"
                    name="shipping_contact_number"
                    id="shipping_contact_number"
                    value="{{ $user->contact_number }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. India"
                    required="required"
                    autocomplete="nope"
                    maxlength="100"
                />
            </div>
        </div>
    </div>
</div>

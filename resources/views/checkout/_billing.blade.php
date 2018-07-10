<div class="card">
    <div class="card-header">
        <h2 class="header p-0 m-0" style="font-size: 22px;">
            Billing Address
        </h2>
    </div>

    <div class="card-body" style="border: 1px solid #ddd">
        <div class="billingAddress mt-5">
            <div class="form-group">
                <label class="normal" for="full_name">Full Name:</label>
                <input
                    type="text"
                    name="full_name"
                    id="full_name"
                    value="{{ $user->getFullName() }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. Hojn Doe"
                    required="required"
                    autocomplete="nope"
                    maxlength="200"
                />
            </div>

            <div class="form-group">
                <label class="normal" for="address_line_1">Address Line 1:</label>
                <input
                    type="text"
                    name="address_line_1"
                    id="address_line_1"
                    value="{{ $billingAddress != null ? $billingAddress->address_line_1 : '' }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. Flat No., Building Name"
                    required="required"
                    autocomplete="nope"
                    maxlength="100"
                />
            </div>

            <div class="form-group">
                <label class="normal" for="address_line_2">Address Line 2: (Optional)</label>
                <input
                    type="text"
                    name="address_line_2"
                    id="address_line_2"
                    value="{{ $billingAddress != null ? $billingAddress->address_line_2 : '' }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. Street Name"
                    autocomplete="nope"
                    maxlength="100"
                />
            </div>

            <div class="form-group">
                <label class="normal" for="area">Area:</label>
                <input
                    type="text"
                    name="area"
                    id="area"
                    value="{{ $billingAddress != null ? $billingAddress->area : '' }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. Station Name"
                    required="required"
                    autocomplete="nope"
                    maxlength="100"
                />
            </div>

            <div class="form-group">
                <label class="normal" for="landmark">Landmark (Optional):</label>
                <input
                    type="text"
                    name="landmark"
                    id="landmark"
                    value="{{ $billingAddress != null ? $billingAddress->landmark : '' }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. Some Famous Restaurant"
                    autocomplete="nope"
                    maxlength="100"
                />
            </div>

            <div class="form-group">
                <label class="normal" for="city">City:</label>
                <input
                    type="text"
                    name="city"
                    id="city"
                    value="{{ $billingAddress != null ? $billingAddress->city : '' }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. Mumbai"
                    required="required"
                    autocomplete="nope"
                    maxlength="100"
                />
            </div>

            <div class="form-group">
                <label class="normal" for="pin_code">Pin / Zip Code:</label>
                <input
                    type="text"
                    name="pin_code"
                    id="pin_code"
                    value="{{ $billingAddress != null ? $billingAddress->pin_code : '' }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. 400034"
                    required="required"
                    autocomplete="nope"
                    maxlength="100"
                />
            </div>

            <div class="form-group">
                <label class="normal" for="state">State:</label>
                <input
                    type="text"
                    name="state"
                    id="state"
                    value="{{ $billingAddress != null ? $billingAddress->state : '' }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. Maharashtra"
                    required="required"
                    autocomplete="nope"
                    maxlength="100"
                />
            </div>

            <div class="form-group">
                <label class="normal" for="country">Country:</label>
                <input
                    type="text"
                    name="country"
                    id="country"
                    value="{{ $billingAddress != null ? $billingAddress->country : '' }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. India"
                    required="required"
                    autocomplete="nope"
                    maxlength="100"
                />
            </div>

            <div class="form-group">
                <label class="normal" for="contact_number">Contact Number:</label>
                <input
                    type="text"
                    name="contact_number"
                    id="contact_number"
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

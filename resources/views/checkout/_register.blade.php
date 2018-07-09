<div class="card">
    <div class="card-header">
        <h2 class="header p-0 m-0" style="font-size: 24px;">
            New User: Register
        </h2>
    </div>

    <div class="card-body" style="border: 1px solid #ddd">
        <form
            action="{{ route('checkout.register') }}"
            method="POST"
            id="formRegisterUser"
            autocomplete="notWanted"
        >
            @csrf

            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label class="normal" for="first_name">First Name:</label>
                        <input
                            type="text"
                            name="first_name"
                            id="first_name"
                            class="form-control hasMaxLength"
                            placeholder="Eg. John"
                            required="required"
                            autocomplete="nope"
                            maxlength="100"
                        />
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label class="normal" for="last_name">Last Name:</label>
                        <input
                            type="text"
                            name="last_name"
                            id="last_name"
                            class="form-control hasMaxLength"
                            placeholder="Eg. John"
                            required="required"
                            autocomplete="nope"
                            maxlength="100"
                        />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label class="normal" for="username">Username:</label>
                        <input
                            type="text"
                            name="username"
                            id="username"
                            class="form-control hasMaxLength"
                            placeholder="Eg. johnDoe"
                            required="required"
                            autocomplete="nope"
                            data-toggle="tooltip"
                            title="It should contain only alphabets, numbers, dashes (-) and underscores ( _ )"
                            maxlength="100"
                        />
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label class="normal" for="email">Email:</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control"
                            placeholder="Eg. john&#64;example.com"
                            data-toggle="tooltip"
                            title="A valid E-Mail address is required"
                            required="required"
                            autocomplete="nope"
                        />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label class="normal" for="password">Password:</label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Eg. Secret"
                            required="required"
                        />
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label class="normal" for="confirm_password">Confirm Password:</label>
                        <input
                            type="password"
                            name="confirm_password"
                            id="confirm_password"
                            class="form-control"
                            placeholder="Eg. Secret"
                            required="required"
                            data-toggle="tooltip"
                            title="It should match with Password field"
                        />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="normal" for="contact_number">Contact Number (Optional):</label>
                <input
                    type="text"
                    name="contact_number"
                    id="contact_number"
                    class="form-control"
                    placeholder="Eg. 9876543210"
                    data-toggle="tooltip"
                    title="It should contain only numbers"
                />
            </div>

            <div class="errorsInRegisteringUser"></div>

            <p class="text-justify mb-3">
                By clicking on Submit, you agree to our
                <a
                    href="javascript:void(0)"
                    target="_blank"
                    class="mainSiteLink"
                >
                    Terms of Service
                </a> and
                <a
                    href="javascript:void(0)"
                    target="_blank"
                    class="mainSiteLink"
                >
                    Privacy Policy
                </a>
            </p>

            <button class="btn submitButton btnRegisterUser">Submit</button>
        </form>
    </div>
</div>

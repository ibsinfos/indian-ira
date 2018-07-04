<form
    action="{{ route('admin.users.edit.updateGeneral', $user->id) }}"
    method="POST"
    id="formUpdateGeneralDetails"
    autocomplete="notWanted"
>
    @csrf

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="form-group">
                <label class="normal" for="username">Username:</label>
                <input
                    type="text"
                    name="username"
                    id="username"
                    value="{{ $user->username }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. johnDoe"
                    data-toggle="tooltip"
                    title="It should contain only alphabets, numbers, dashes (-) and underscores ( _ )"
                    maxlength="50"
                />
            </div>
        </div>

        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="form-group">
                <label class="normal" for="email">E-Mail Address:</label>
                <input
                    type="text"
                    id="email"
                    name="email"
                    value="{{ $user->email }}"
                    class="form-control"
                    placeholder="Eg. johnDoe&#64;example.com"
                    data-toggle="tooltip"
                    title="A valid E-Mail address is required"
                />
            </div>
        </div>

        <div class="col-xl-4 col-lg-4 col-md-6">
            <div class="form-group">
                <label class="normal" for="first_name">First Name:</label>
                <input
                    type="text"
                    name="first_name"
                    id="first_name"
                    value="{{ $user->first_name }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. John Doe"
                    required="required"
                    autocomplete="nope"
                    maxlength="100"
                />
            </div>
        </div>

        <div class="col-xl-4 col-lg-4 col-md-6">
            <div class="form-group">
                <label class="normal" for="last_name">Last Name:</label>
                <input
                    type="text"
                    name="last_name"
                    id="last_name"
                    value="{{ $user->last_name }}"
                    class="form-control hasMaxLength"
                    required="required"
                    placeholder="Eg. Doe"
                    autocomplete="nope"
                    maxlength="100"
                />
            </div>
        </div>

        <div class="col-xl-4 col-lg-4 col-md-6">
            <div class="form-group">
                <label class="normal" for="contact_number">Contact Number (Optional):</label>
                <input
                    type="text"
                    name="contact_number"
                    id="contact_number"
                    value="{{ $user->contact_number }}"
                    class="form-control hasMaxLength"
                    placeholder="Eg. 9876543210"
                    autocomplete="nope"
                />
            </div>
        </div>
    </div>

    <div class="errorsInUpdatingGeneralDetails"></div>

    <button class="btn submitButton btnGeneralDetails mt-3">Submit</button>
</form>

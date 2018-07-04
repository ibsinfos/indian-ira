
<form
    action="{{ route('admin.users.edit.updatePassword', $user->id) }}"
    method="POST"
    id="formUpdateChangePassword"
    autocomplete="notWanted"
>
    @csrf

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="form-group">
                <label class="normal" for="new_password">New Password:</label>
                <input
                    type="password"
                    name="new_password"
                    id="new_password"
                    class="form-control"
                    placeholder="Eg. Password"
                    required="required"
                />
            </div>
        </div>

        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="form-group">
                <label class="normal" for="repeat_new_password">Repeat New Password:</label>
                <input
                    type="password"
                    name="repeat_new_password"
                    id="repeat_new_password"
                    class="form-control"
                    placeholder="Eg. Password"
                    required="required"
                />
            </div>
        </div>
    </div>

    <div class="errorsInUpdatingChangePassword"></div>

    <button class="btn submitButton btnChangePassword mt-3">Submit</button>
</form>

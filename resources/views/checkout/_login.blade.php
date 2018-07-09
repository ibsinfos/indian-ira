<div class="card">
    <div class="card-header">
        <h2 class="header p-0 m-0" style="font-size: 24px;">
            Existing User: Login
        </h2>
    </div>

    <div class="card-body" style="border: 1px solid #ddd">
        <form
            action="{{ route('checkout.postLogin') }}"
            method="POST"
            id="formLoginUser"
            autocomplete="notWanted"
        >
            @csrf

            <div class="form-group">
                <label class="normal" for="usernameOrEmail">Username or E-Mail:</label>
                <input
                    type="text"
                    name="usernameOrEmail"
                    id="usernameOrEmail"
                    class="form-control hasMaxLength"
                    placeholder="Eg. johnDoe / johndoe&#64;example.com"
                    required="required"
                    autocomplete="nope"
                    data-toggle="tooltip"
                    title="It should contain only alphabets, numbers, dashes (-) and underscores ( _ ) or a valid E-Mail address"
                    data-placement="right"
                    maxlength="100"
                />
            </div>

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

            <div class="errorsInLoggingUser"></div>

            <button class="btn submitButton btnLoginUser">Submit</button>
        </form>
    </div>
</div>

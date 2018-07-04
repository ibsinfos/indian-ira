<div class="mb-3">
    <div class="row">
        <div class="col-md-12">
            <div class="float-right">
                <a
                    href="{{ route('admin.users.edit', $user->id) }}?general-details"
                    class="btn @if (request()->exists('general-details')) btn-dark @else btn-outline-dark @endif btn-sm shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                >General Details</a>
                <a
                    href="{{ route('admin.users.edit', $user->id) }}?billing-address"
                    class="btn @if (request()->exists('billing-address')) btn-dark @else btn-outline-dark @endif btn-sm shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                >Billing Address</a>
                <a
                    href="{{ route('admin.users.edit', $user->id) }}?change-password"
                    class="btn @if (request()->exists('change-password')) btn-dark @else btn-outline-dark @endif btn-sm shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                >Change Password</a>
            </div>
        </div>
    </div>
</div>

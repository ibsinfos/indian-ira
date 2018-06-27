{{-- @extends('emails.layout')

@section('title_and_meta')
    <title>Forgot Password</title>
@endsection

@section('content')
    <h2 class="no-margin-padding subject">
        Forgot Password
    </h2>

    <p class="text mb-20px">
        Dear {{ $password->email }},
    </p>

    <p class="text mb-20px">

    </p>

    <div class="linkButton">

    </div>
@endsection --}}


@component('mail::message')
# Forgot Password

Dear {{ $data->email }},

Click the below button to reset / change your password.

@component('mail::button', ['url' => route('users.resetPassword', $data->token), 'color' => 'red'])
Reset Password
@endcomponent

<div style="margin-bottom: 20px;"></div>

<p style="margin-bottom: 20px;">
    Also note that the above link will be active only for 1 hour from the time it was sent.<br />
    So, the link will get expired on {{ $data->dateAndTime('expires_on') }} IST.
</p>

<div style="margin-bottom: 20px;"></div>

Thank You.<br><br>
Team {{ config('app.name') }}

<div class="systemGenerated">
    This is a system generated E-Mail. Kindly do not reply to this E-Mail address.
</div>
@endcomponent

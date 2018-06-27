@component('mail::message')
# Registration Successful

Dear {{ $user->getFullName() }},

Your registration on {{ config('app.name') }} website was Successful.

Kindly click the button below to login.

@component('mail::button', ['url' => route('users.login'), 'color' => 'red'])
Login in your account
@endcomponent

Thank You.<br><br>
Team {{ config('app.name') }}

<div class="systemGenerated">
    This is a system generated E-Mail. Kindly do not reply to this E-Mail address.
</div>
@endcomponent

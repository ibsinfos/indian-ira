@component('mail::message')
# {{ config('app.name') }} - Confirm Your Registration

Dear {{ $user->getFullName() }},

You have provided the following details at the time of registration

@component('mail::table')
|                   |      |
|              ---: | :--- |
| **Username:**     | <span style="margin-left: 10px;">{{ $user->username }}</span>      |
| **Full Name:**    | <span style="margin-left: 10px;">{{ $user->getFullName() }}</span> |
| **E-Mail:**       | <span style="margin-left: 10px;">{{ $user->email }}</span>         |
| **Contact:**      | <span style="margin-left: 10px;">{{ $user->contact_number }}</span> |
| **Registered On:** | <span style="margin-left: 10px;">{{ $user->formatsCreatedAt() }} IST</span> |
@endcomponent

Kindly confirm your account registration on {{ config('app.name') }} by clicking on the button below.

@component('mail::button', [
    'url'   => route('users.confirmRegistration', $user->verification_token),
    'color' => 'red'
])
Confirm Your Account
@endcomponent

Note that the above confirmation button is <strong style="color: #f14444;">active and valid only for 24 hours</strong> since the time of your account's registration.

Thank You.<br><br>
Team {{ config('app.name') }}

<div class="systemGenerated">
    This is a system generated E-Mail. Kindly do not reply to this E-Mail address.
</div>
@endcomponent

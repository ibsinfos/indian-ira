@component('mail::message')
# Super Administrator Generated Successfully

Dear {{ $admin->getFullName() }},

You have successfully registered yourself as the Administrator. Kindly review the registration details below.

@component('mail::table')
|                   |      |
|              ---: | :--- |
| **Username:**     | <span style="margin-left: 10px;">{{ $admin->username }}</span>      |
| **Full Name:**    | <span style="margin-left: 10px;">{{ $admin->getFullName() }}</span> |
| **E-Mail:**       | <span style="margin-left: 10px;">{{ $admin->email }}</span>         |
| **Password:**     | <span style="margin-left: 10px;">{{ session('password') }}</span>   |
| **Generated On:** | <span style="margin-left: 10px;">{{ \Carbon\Carbon::parse($admin->created_at)->timezone('Asia/Kolkata')->format('D jS M Y, h:i A') }} IST</span> |
@endcomponent

@component('mail::button', ['url' => route('homePage')])
Go to Home Page
@endcomponent

Thank You.<br><br>
Team {{ config('app.name') }}

<div class="systemGenerated">
    This is a system generated E-Mail. Kindly do not reply to this E-Mail address.
</div>
@endcomponent

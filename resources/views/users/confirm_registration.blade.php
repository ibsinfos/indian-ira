@extends('users.partials._layout')

@section('title_and_meta_info')
    <title>Confirmation Pending</title>
@endsection

@section('pageStyles')
<style>
    body .container-fluid {
        width: 100%;
    }

    @media screen and (max-width: 767px) {
        body {
            padding-top: 60px;
        }
    }
</style>
@endsection

@section('content')
    <div class="container">
        <figure class="text-center mb-0">
            <img
                src="{{ url('/images/Indian-Ira-Logo.png') }}"
                alt="{{ config('app.name') }} - Logo"
                class="img img-responsive logoImage"
            />
        </figure>

        <div class="page-header">
            <h2>Confirmation Pending</h2>
        </div>

        <p>Thank you for registering with {{ config('app.name') }}. We have sent an E-Mail for confirming your E-Mail address.</p>

        <p>Click the <span style="font-weight: bold;">"Confirm Your Account"</span> button provided in that E-Mail.</p>

        <p>Note that the confirmation button is <span style="font-weight: bold;">active and valid only for 24 hours</span> since the time of your account's registration.</p>

        <p class="text-danger" style="font-weight: bold; margin-top: 30px; text-decoration: underline;">
            Note: You will NOT BE able to login unless you confirm your account.
        </p>

        <div style="margin-bottom: 30px;"></div>

        <p>Click the below button in case you didn't received the Confirmation E-Mail:</p>

        <div style="margin-bottom: 30px;"></div>

        <a
            href="{{ route('users.resendConfimationMail') }}"
            @if (session('clickedResendConfirmLinkButton'))
                class="btn btn-warning btnConfirmAccount disabled"
            @else
                class="btn btn-warning btnConfirmAccount"
            @endif
            style="margin-bottom: 10px;"
        >
            Resend Confirmation Link
        </a>
        @if (session('clickedResendConfirmLinkButton'))
            <p class="text-danger resendNote" style="font-weight: bold; text-decoration: underline;">
                Note: The above button will get reactivated after 2 minutes.
            </p>
        @endif
    </div>

    <div style="margin-bottom: 50px;"></div>
@endsection

@section('pageScripts')
    <script>
        if ($('.btnConfirmAccount').hasClass('disabled')) {
            setTimeout(function () {
                $('.resendNote').hide();

                @php
                session()->forget('clickedResendConfirmLinkButton');
                @endphp

                return $('.btnConfirmAccount').removeClass('disabled');
            }, 120000);
        }
    </script>
@endsection

<div class="card">
    <div class="card-header">
        <h2 class="header p-0 m-0" style="font-size: 22px;">
            Choose Payment Method
        </h2>
    </div>

    <div class="card-body" style="border: 1px solid #ddd">
        <div class="mt-2">

            @foreach ($paymentMethods as $paymentOption)
                <div class="form-group">
                    <input
                        type="radio"
                        name="payment_method"
                        id="payment_method_{{ $paymentOption }}"
                        value="{{ $paymentOption }}"
                    >
                    <label
                        for="payment_method_{{ $paymentOption }}"
                        data-toggle="tooltip"
                        @if ($paymentOption == 'online')
                            title="You will be taken to Payment Gateway for making the payment"
                        @elseif ($paymentOption == 'offline')
                            title="You will be shown Our Bank details for you to make the payment"
                        @elseif ($paymentOption == 'cod')
                            title="Payment shall be done to the courier delivery person"
                        @endif
                    >
                        {{ title_case($paymentOption) }}

                        @if ($paymentOption == 'online')
                            (Via Credit Card / Debit Card / Net Banking etc)
                        @elseif ($paymentOption == 'offline')
                            (Via Cheque / Demand Draft (DD) / Direct Cash Deposit in Bank etc)
                        @elseif ($paymentOption == 'cod')
                            (Extra <i class="fas fa-rupee-sign"></i> {{ number_format(\IndianIra\GlobalSettingCodCharge::first()->amount, 2) }} to be given to the delivery person)
                        @endif
                    </label>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="header p-0 m-0" style="font-size: 22px;">
            Choose Payment Method
        </h2>
    </div>

    <div class="card-body" style="border: 1px solid #ddd">
        <div class="mt-2">
            <div class="form-group">
                <input
                    type="radio"
                    name="payment_method"
                    id="payment_method_online"
                    value="online"
                    checked="checked"
                >
                <label
                    for="payment_method_online"
                    title="You will be taken to Payment Gateway for making the payment"
                    data-toggle="tooltip"
                >Online (Via Credit Card / Debit Card / Net Banking etc)</label>
            </div>
            <div class="form-group">
                <input
                    type="radio"
                    name="payment_method"
                    id="payment_method_offline"
                    value="offline"
                >
                <label
                    for="payment_method_offline"
                    title="You will be shown Our Bank details for you to make the payment"
                    data-toggle="tooltip"
                >
                    Offline (Via Cheque / Demand Draft (DD) / Direct Cash Deposit in Bank etc)</label>
            </div>

            <div class="form-group">
                <input
                    type="radio"
                    name="payment_method"
                    id="payment_method_cod"
                    value="cod"
                >
                <label
                    for="payment_method_cod"
                    title="Payment shall be done to the courier delivery person"
                    data-toggle="tooltip"
                >
                    Cash On Delivery
                    (Extra <i class="fas fa-rupee-sign"></i> {{ number_format(\IndianIra\GlobalSettingCodCharge::first()->amount, 2) }} to be given to the delivery person)
                </label>
            </div>
        </div>
    </div>
</div>

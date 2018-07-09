<div
    class="modal fade"
    id="selectLocationModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Select Location</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                @if (session('cart'))
                    <p class="text-danger mb-5">
                        This is one time selection only. You will not be allowed to change this value at the time of updating the Billing and Shipping Address in the Checkout section.
                    </p>

                    <div class="form-group">
                        <label class="normal" for="location">Select the shipping location:</label>
                        <select name="location" id="location" class="selectLocation">
                            <optgroup label="Indian Cities">
                                @foreach ($shippingRatesCity as $shippingRate)
                                    <option value="{{ $shippingRate }}">
                                        {{ $shippingRate }}
                                    </option>
                                @endforeach
                            </optgroup>

                            <optgroup label="Indian States / Union Territories">
                                @foreach ($shippingRatesState as $shippingRate)
                                    <option value="{{ $shippingRate }}">
                                        {{ $shippingRate }}
                                    </option>
                                @endforeach
                            </optgroup>

                            <optgroup label="Countries">
                                @foreach ($shippingRatesCountry as $shippingRate)
                                    <option value="{{ $shippingRate }}">
                                        {{ $shippingRate }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                @else
                    <p class="text-danger">
                        Kindly add products in the cart for calculating the shipping rate.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

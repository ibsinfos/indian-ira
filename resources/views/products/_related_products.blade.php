@if ($relatedProducts->isNotEmpty())
    <div class="container">
        <h4 class="mb-3 p-0 font-weight-bold">Related Products</h4>

        <div class="owl-carousel owl-theme mb-5">
            @foreach ($relatedProducts as $relatedProduct)
                <div class="item mx-1">
                    <div class="card">
                        <img
                            src="{{ url($relatedProduct->catalogueImage()) }}"
                            alt="{{ $relatedProduct->name }}"
                            class="mb-4"
                        >

                        <div class="p-2">
                            <div class="productName mb-3">
                                <a href="{{ $relatedProduct->pageUrl() }}" class="mainSiteLink">
                                    @if(strlen($relatedProduct->name) > 40)
                                        {{ substr(title_case($relatedProduct->name), 0, 40) . '...' }}
                                    @else
                                        {{ title_case($relatedProduct->name) }}
                                    @endif
                                </a>
                            </div>

                            <div class="productPrice mb-3">
                                <i class="fas fa-rupee-sign"></i>
                                @php
                                $options = $relatedProduct->options->sortBy('sort_number');

                                $option = $options->last();

                                if ($option && $option->discount_price > 0.0) {
                                    $price = $option->discount_price;
                                } elseif ($option && $option->discount_price <= 0.0) {
                                    $price = $option->selling_price;
                                }
                                @endphp
                                {{ number_format($price, 2) }}
                            </div>

                            @php
                            $link = route('cart.add', $relatedProduct->code);
                            if ($relatedProduct->number_of_options >= 1) {
                                $link = route('cart.add', [
                                    $relatedProduct->code, $options->first()->option_code
                                ]);
                            }
                            @endphp

                            <a href="{{ $link }}" class="btn btn-dark btn-sm btnAddToCart">
                                Add To Cart
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mb-5"></div>
    </div>
@endif

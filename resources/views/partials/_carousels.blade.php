<section class="mt-5">
    <div class="container">
        @foreach ($carousels as $carousel)
            <h3>{{ $carousel->name }}</h3>

            <div class="row">
                <div class="col-lg-12">
                    <div class="owl-carousel owl-theme mb-5">
                        @foreach ($carousel->products as $product)
                            <div class="item mx-1">
                                <div class="card">
                                    <img
                                        src="{{ $product->catalogueImage() }}"
                                        alt="{{ $product->name }}"
                                        class="mb-4"
                                    >

                                    <div class="p-2">
                                        <div class="productName mb-3">
                                            <a href="javascript:void(0)" class="mainSiteLink">
                                                @if(strlen($product->name) > 40)
                                                    {{ substr(title_case($product->name), 0, 40) . '...' }}
                                                @else
                                                    {{ title_case($product->name) }}
                                                @endif
                                            </a>
                                        </div>

                                        <div class="productPrice mb-3">
                                            <i class="fas fa-rupee-sign"></i>
                                            {{ number_format($product->options->last()->selling_price, 2) }}
                                        </div>

                                        <a href="javascript:void(0)" class="btn btn-dark btn-sm">
                                            Add To Cart
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

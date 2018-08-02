<section class="mt-5">
    <div class="container">
        @foreach ($carousels as $carousel)
            <h1
                class="mb-2 font-weight-bold text-uppercase"
                style="font-family: 'Raleway', sans-serif; font-size: 1.8rem;"
            >{{ $carousel->name }}</h1>

            <div class="row">
                <div class="col-lg-12">
                    <div class="owl-carousel owl-theme mb-5">
                        @foreach ($carousel->products as $product)
                            <div class="item mx-1">
                                @include('partials._product_card')
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

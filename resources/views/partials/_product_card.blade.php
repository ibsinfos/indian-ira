@php
$options = $product->options()->onlyEnabled()->get()->sortBy('sort_number');
$option = $options->first();
@endphp

<div class="card" style="border: 1px solid #000;">
    <img
        src="{{ url($product->catalogueImage()) }}"
        alt="{{ $product->name }}"
        class="mb-4 w-100"
    >

    <div class="p-2">
        <div class="productName mb-3">
            @if (isset($tag) && $tag != null)
                <a href="{{ $tag->productPageUrl($product) }}" class="mainSiteLink">
            @else
                <a href="{{ $product->pageUrl() }}" class="mainSiteLink">
            @endif
                @if(strlen($product->name) > 40)
                    {{ substr(title_case($product->name), 0, 40) . '...' }}
                @else
                    {{ title_case($product->name) }}
                @endif
            </a>
        </div>

        <div class="productPrice mb-3">
            <i class="fas fa-rupee-sign"></i>
            @php
            if ($option && $option->discount_price > 0.0) {
                $price = $option->discount_price;
            } elseif ($option && $option->discount_price <= 0.0) {
                $price = $option->selling_price;
            } else {
                $price = 0.0;
            }
            @endphp
            {{ number_format($price, 2) }}
        </div>

        @php
        $link = route('cart.add', $product->code);
        if ($product->number_of_options >= 1) {
            $link = route('cart.add', [
                $product->code, $option->option_code
            ]);
        }
        @endphp

        @if ($option->stock >= 1)
            <a
                href="{{ $link }}"
                class="btn btn-dark btn-sm btnAddToCart text-uppercase mb-1"
            >
                Add To Cart
            </a>
        @else
            <a
                href="javascript:void(0)"
                class="btn btn-dark btn-sm btnAddToCart text-uppercase mb-1"
            >
                Out of Stock
            </a>
        @endif

        <a
            href="#"
            class="mainSiteLink font-weight-bold"
            data-toggle="modal"
            data-target="#enquireProductModal"
            data-product="{{ $product }}"
            data-option="{{ $option }}"
        >
            Enquire
        </a>
    </div>
</div>

<div class="mb-3"></div>

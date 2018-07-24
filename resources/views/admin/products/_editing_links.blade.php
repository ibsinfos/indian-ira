<div class="mb-3">
    <div class="row">
        <div class="col-md-12">
            <div class="float-right">
                <a
                    href="{{ route('admin.products.edit', $product->id) }}?general"
                    class="btn @if (request()->exists('general')) btn-dark @else btn-outline-dark @endif btn-sm shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                >General Details</a>
                <a
                    href="{{ route('admin.products.edit', $product->id) }}?detailed-information"
                    class="btn @if (request()->exists('detailed-information')) btn-dark @else btn-outline-dark @endif btn-sm shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                >Detailed Information</a>
                <a
                    href="{{ route('admin.products.edit', $product->id) }}?meta-information"
                    class="btn @if (request()->exists('meta-information')) btn-dark @else btn-outline-dark @endif btn-sm shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                >Meta Information</a>
                <a
                    href="{{ route('admin.products.edit', $product->id) }}?image"
                    class="btn @if (request()->exists('image')) btn-dark @else btn-outline-dark @endif btn-sm shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                >Image Details</a>
                <a
                    href="{{ route('admin.products.edit', $product->id) }}?inter-related"
                    class="btn @if (request()->exists('inter-related')) btn-dark @else btn-outline-dark @endif btn-sm shadow-none mt-md-0 mt-lg-0 mt-xl-0 mt-sm-4"
                >Inter Related Products</a>
            </div>
        </div>
    </div>
</div>

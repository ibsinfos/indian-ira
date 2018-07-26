<table class="table table-striped table-bordered allProductsSortNumberTable" data-page-length="100">
    <thead>
        <th>Sr. No.</th>
        <th>Product Details</th>
        <th>Sort Number</th>
        <th>Action</th>
    </thead>

    <tbody>
        @foreach ($products as $index => $product)
            <tr>
                <td>{{ ++$index }}</td>
                <td>
                    @if ($product->hasUploadedImageFile())
                        <img
                            src="{{ url($product->cartImage()) }}"
                            alt="{{ title_case($product->name) }}"
                            class="img-fluid float-left mr-3"
                        >
                    @elseif($product->hasUploadedImageFile())
                        <img
                            src="{{ url($product->cartImage()) }}"
                            alt="{{ title_case($product->name) }}"
                            class="img-fluid float-left mr-3"
                        >
                    @else
                        <img
                            src="{{ url('/images/no-image.jpg') }}"
                            alt="Image Not Found"
                            class="img-fluid float-left mr-3"
                        />
                    @endif

                    <a href="{{ $product->canonicalPageUrl() }}" target="_blank" class="mainSiteLink font-weight-bold">
                        {{ title_case($product->name) }}
                    </a><br /><br />
                    {{ $product->code }}
                </td>
                <td>{{ $product->sort_number }}</td>
                <td>
                    <a
                        href="#"
                        class="btn btn-sm btn-info"
                        data-toggle="modal"
                        data-target="#editProductSortNumberModal"
                        data-product="{{ $product }}"
                    >
                        <i class="fas fa-pencil-alt"></i> Edit
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

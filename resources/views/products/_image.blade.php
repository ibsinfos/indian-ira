<div class="bg-white imgZoomContainer">
    @if ($product->number_of_options >= 1)
        <img
            src="{{ url($option->zoomedImage()) }}"
            alt="{{ $product->name }}"
            id="productImage"
            class="img-fluid"
            style="margin-bottom: 20px;"
            data-image-zoom="{{ url($option->zoomedImage()) }}"
        >
    @else
        <img
            src="{{ url($product->zoomedImage()) }}"
            alt="{{ $product->name }}"
            id="productImage"
            class="img-fluid"
            style="margin-bottom: 20px;"
            data-image-zoom="{{ url($product->zoomedImage()) }}"
        >
    @endif
</div>

<div id="alternateSnaps" class="mt-3">
    @if ($option->hasUploadedImageFile())
        <a
            href="#"
            class="w-100 mainSiteLink galImg0"
            data-image="{{ url($option->zoomedImage('image')) }}"
            data-zoom-image="{{ url($option->zoomedImage('image')) }}"
        >
            <img
                src="{{ url($option->cartImage('image')) }}"
                alt="{{ $product->name }}"
                id="productImage"
            />
        </a>
    @elseif ($product->hasUploadedImageFile())
        <a
            href="#"
            class="w-100 mainSiteLink galImg0"
            data-image="{{ url($product->zoomedImage('images')) }}"
            data-zoom-image="{{ url($product->zoomedImage('images')) }}"
        >
            <img
                src="{{ url($product->cartImage('images')) }}"
                alt="{{ $product->name }}"
                id="productImage"
            />
        </a>
    @endif

    @if ($galleryImg->gallery_image_1 != null || $galleryImg->gallery_image_1 != '')
        <a
            href="#"
            class="w-100 mainSiteLink galImg1"
            data-image="{{ url($galleryImg->zoomedImage('gallery_image_1')) }}"
            data-zoom-image="{{ url($galleryImg->zoomedImage('gallery_image_1')) }}"
        >
            <img
                src="{{ url($galleryImg->cartImage('gallery_image_1')) }}"
                alt="{{ $product->name }}"
                id="productImage"
            />
        </a>
    @endif

    @if ($galleryImg->gallery_image_2 != null || $galleryImg->gallery_image_2 != '')
        <a
            href="#"
            class="w-100 mainSiteLink galImg2"
            data-image="{{ url($galleryImg->zoomedImage('gallery_image_2')) }}"
            data-zoom-image="{{ url($galleryImg->zoomedImage('gallery_image_2')) }}"
        >
            <img
                src="{{ url($galleryImg->cartImage('gallery_image_2')) }}"
                alt="{{ $product->name }}"
                id="productImage"
            />
        </a>
    @endif

    @if ($galleryImg->gallery_image_3 != null || $galleryImg->gallery_image_3 != '')
        <a
            href="#"
            class="w-100 mainSiteLink galImg3"
            data-image="{{ url($galleryImg->zoomedImage('gallery_image_3')) }}"
            data-zoom-image="{{ url($galleryImg->zoomedImage('gallery_image_3')) }}"
        >
            <img
                src="{{ url($galleryImg->cartImage('gallery_image_3')) }}"
                alt="{{ $product->name }}"
                id="productImage"
            />
        </a>
    @endif
</div>

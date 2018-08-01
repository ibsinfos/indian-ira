@if ($product->tags->isNotEmpty())
    <div class="mb-5"></div>

    <p class="font-weight-bold">Tags:</p>

    @foreach ($product->tags as $tag)
        <a
            href="{{ url($tag->pageUrl()) }}"
            class="btn btn-sm btn-outline-danger mb-3"
            target="_blank"
            data-toggle="tooltip"
            data-placement="right"
            title="{{ $tag->short_description }}"
        >
            {{ title_case($tag->name) }}
        </a>
    @endforeach
@endif

<div class="mb-5"></div>

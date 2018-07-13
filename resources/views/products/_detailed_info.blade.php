<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    <li class="nav-item" style="font-size: 1rem;">
        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Description</a>
    </li>

    @if ($product->additional_notes != null || $product->additional_notes != '')
        <li class="nav-item" style="font-size: 1rem;">
            <a class="nav-link" id="pills-additional-notes-tab" data-toggle="pill" href="#pills-additional-notes" role="tab" aria-controls="pills-additional-notes" aria-selected="false">Additional Notes</a>
        </li>
    @endif

    @if ($product->terms != null || $product->terms != '')
        <li class="nav-item" style="font-size: 1rem;">
            <a class="nav-link" id="pills-terms-tab" data-toggle="pill" href="#pills-terms" role="tab" aria-controls="pills-terms" aria-selected="false">Terms</a>
        </li>
    @endif
</ul>

<div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
        {!! $product->description !!}
    </div>

    @if ($product->additional_notes != null || $product->additional_notes != '')
        <div class="tab-pane fade" id="pills-additional-notes" role="tabpanel" aria-labelledby="pills-additional-notes-tab">
            {!! $product->additional_notes !!}
        </div>
    @endif

    @if ($product->additional_notes != null || $product->additional_notes != '')
        <div class="tab-pane fade" id="pills-terms" role="tabpanel" aria-labelledby="pills-terms-tab">
            {!! $product->terms !!}
        </div>
    @endif
</div>

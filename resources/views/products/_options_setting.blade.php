@if ($product->number_of_options == 1)
    <div class="form-group">
        <label for="optionValue1" class="font-weight-bold">
            Select {{ $options->sortBy('sort_number')->first()->option_1_heading }}
        </label>
        <select name="optionValue1" id="optionValue1" class="form-control optionValue1">
            @foreach ($options->sortBy('sort_number') as $opt)
                <option
                    value="{{ $opt->code }}"
                    data-optid="{{ $opt }}"
                >{{ $opt->option_1_value }}</option>
            @endforeach
        </select>
    </div>
@endif

@if ($product->number_of_options == 2)
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="optionValue1" class="font-weight-bold">
                    Select {{ $options->sortBy('sort_number')->first()->option_1_heading }}
                </label>
                <select name="optionValue1" id="optionValue1" class="form-control optionValue1">
                    @php
                    $all1stOptions = collect();
                    $arr = [];
                    foreach ($options->sortBy('sort_number') as $opt) {
                        if (! in_array($opt->option_1_value, $arr)) {
                            $all1stOptions->push($opt);
                        }

                        $arr[] = $opt->option_1_value;
                    }
                    @endphp

                    @foreach ($all1stOptions as $opt)
                        @php
                        $o = \IndianIra\ProductPriceAndOption::whereProductId($product->id)
                                ->where('option_1_value', $opt->option_1_value)
                                ->orderBy('sort_number', 'ASC')
                                ->get();
                        @endphp
                        <option
                            value="{{ $opt->code }}"
                            data-optid="{{ $opt }}"
                            data-opt2id="{{ json_encode($o, JSON_UNESCAPED_SLASHES) }}"
                        >{{ $opt->option_1_value }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="optionValue2" class="font-weight-bold">
                    Select {{ $options->sortBy('sort_number')->first()->option_2_heading }}
                </label>
                <select name="optionValue2" id="optionValue2" class="form-control optionValue2">
                    @foreach ($options->sortBy('sort_number') as $opt)
                        <option
                            value="{{ $opt->code }}"
                            data-optid="{{ $opt }}"
                        >{{ $opt->option_2_value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
@endif

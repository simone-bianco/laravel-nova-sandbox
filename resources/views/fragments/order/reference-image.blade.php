@if (isset($reference) && !empty($reference))
    <div style="width: 100px; text-align: center;">
        @switch($reference)
            @case(\App\Models\Interfaces\OrderSources::SOURCE_MAGENTO1)
                <img src="{{ url('images/logos/magento1_logo.svg') }}" style="max-width: 100px; max-height: 100px; height: auto; display: inline-block; vertical-align: middle;" alt="{{ $reference }}">
                @break
            @case(\App\Models\Interfaces\OrderSources::SOURCE_MAGENTO2)
                <img src="{{ url('images/logos/magento2_logo.png') }}" style="max-width: 100px; max-height: 100px; height: auto; display: inline-block; vertical-align: middle;" alt="{{ $reference }}">
                @break
            @case(\App\Models\Interfaces\OrderSources::SOURCE_MANOMANO)
                <img src="{{ url('images/logos/manomano_logo.png') }}" style="max-width: 100px; max-height: 100px; height: auto; display: inline-block; vertical-align: middle;" alt="{{ $reference }}">
                @break
            @case(\App\Models\Interfaces\OrderSources::SOURCE_MIRAKL)
                <img src="{{ url('images/logos/mirakl_logo.png') }}" style="max-width: 100px; max-height: 100px; height: auto; display: inline-block; vertical-align: middle;" alt="{{ $reference }}">
                @break
            @case(\App\Models\Interfaces\OrderSources::SOURCE_AMAZON)
                <img src="{{ url('images/logos/amazon_logo.png') }}" style="max-width: 100px; max-height: 100px; height: auto; display: inline-block; vertical-align: middle;" alt="{{ $reference }}">
                @break
            @case(\App\Models\Interfaces\OrderSources::SOURCE_EBAY)
                <img src="{{ url('images/logos/ebay_logo.png') }}" style="max-width: 100px; max-height: 100px; height: auto; display: inline-block; vertical-align: middle;" alt="{{ $reference }}">
                @break
            @case(\App\Models\Interfaces\OrderSources::SOURCE_BIGCOMMERCE)
                <img src="{{ url('images/logos/bigcommerce_logo.png') }}" style="max-width: 100px; max-height: 100px; height: auto; display: inline-block; vertical-align: middle;" alt="{{ $reference }}">
                @break
            @default
                <p>{{ $reference }}</p>
        @endswitch
    </div>
@endif

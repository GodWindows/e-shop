@php
    $hasDiscount = $product->discount_price != -1;
    $finalPrice  = $hasDiscount ? $product->discount_price : $product->price;
    $percentOff  = ($hasDiscount && $product->price > 0)
        ? (int) round(100 - ($product->discount_price * 100 / $product->price))
        : 0;
    $cardCols = $cardCols ?? 'col-lg-3 col-md-4 col-6';
@endphp
<div class="{{ $cardCols }} pb-1">
    <div class="product-item bg-light mb-4">
        <div class="product-img position-relative overflow-hidden">
            @if ($percentOff > 0)
                <span class="product-badge bg-primary text-dark">-{{ $percentOff }}%</span>
            @endif
            <a href="{{ route('product.view', $product->id) }}">
                <div class="product-img-fixed">
                    <img src="{{ asset('storage/' . image($product)) }}" alt="{{ $product->name }}">
                </div>
            </a>
        </div>
        <div class="text-center py-4">
            <a class="h6 text-decoration-none text-truncate product-name px-2" href="{{ route('product.view', $product->id) }}">
                {{ $product->name }}
            </a>
            <div class="d-flex align-items-center justify-content-center mt-2">
                <h5 class="mb-0">{{ number_format($finalPrice, 0, ',', ' ') }} F</h5>
                @if ($hasDiscount)
                    <h6 class="text-muted ml-2 mb-0"><del>{{ number_format($product->price, 0, ',', ' ') }} F</del></h6>
                @endif
            </div>
        </div>
        <div class="d-flex justify-content-between border-top">
            <a class="btn btn-sm text-dark p-0 pl-3 py-2" href="{{ route('product.view', $product->id) }}">
                <i class="fas fa-eye text-primary mr-1"></i>{{ __('Voir') }}
            </a>
            <input type="hidden" id="itemCount_{{ $product->id }}" value="1">
            <button type="button" class="btn btn-sm text-dark p-0 pr-3 py-2" onclick="addToCart({{ $product->id }})">
                <i class="fas fa-shopping-cart text-primary mr-1"></i>{{ __('Ajouter') }}
            </button>
        </div>
    </div>
</div>

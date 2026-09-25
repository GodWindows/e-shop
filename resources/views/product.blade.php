@extends('layouts.shop')

@section('page_title')
    {{ $product->name }} - {{ env('SHOP_NAME') }}
@endsection

@section('content')

    @php
        $productCategory = $categories->firstWhere('id', $product->category_id);
        $productImages   = removeEmptyValuesFromArray(json_decode($product->images));
        $hasDiscount     = $product->discount_price != -1;
        $finalPrice      = $hasDiscount ? $product->discount_price : $product->price;
        $percentOff      = ($hasDiscount && $product->price > 0)
            ? (int) round(100 - ($product->discount_price * 100 / $product->price))
            : 0;
    @endphp

    <!-- Fil d'ariane Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{ route('welcome') }}">{{ __('Accueil') }}</a>
                    <a class="breadcrumb-item text-dark" href="{{ route('shop') }}">{{ __('Produits') }}</a>
                    @if ($productCategory)
                        <a class="breadcrumb-item text-dark" href="{{ route('shop', $productCategory->id) }}">{{ $productCategory->name }}</a>
                    @endif
                    <span class="breadcrumb-item active">{{ $product->name }}</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Fil d'ariane End -->


    <!-- Fiche produit Start -->
    <div class="container-fluid pb-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 mb-30">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner bg-light">
                        @foreach ($productImages as $image)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}">
                            </div>
                        @endforeach
                    </div>
                    @if (count($productImages) > 1)
                        <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                            <i class="fa fa-2x fa-angle-left text-dark"></i>
                        </a>
                        <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                            <i class="fa fa-2x fa-angle-right text-dark"></i>
                        </a>
                    @endif
                </div>

                @if (count($productImages) > 1)
                    <div class="d-flex justify-content-center mt-3">
                        @foreach ($productImages as $image)
                            <a href="#product-carousel" data-target="#product-carousel" data-slide-to="{{ $loop->index }}" class="d-block bg-light mr-2" style="width: 70px; height: 70px;">
                                <img class="w-100 h-100" style="object-fit: cover;" src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}">
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-lg-7 h-auto mb-30">
                <div class="h-100 bg-light p-30">
                    <h3 class="mb-3">{{ $product->name }}</h3>

                    <div class="d-flex align-items-center mb-3">
                        <h3 class="font-weight-semi-bold mb-0">{{ number_format($finalPrice, 0, ',', ' ') }} F</h3>
                        @if ($hasDiscount)
                            <h5 class="text-muted ml-3 mb-0"><del>{{ number_format($product->price, 0, ',', ' ') }} F</del></h5>
                            @if ($percentOff > 0)
                                <span class="badge badge-primary text-dark ml-3 p-2">-{{ $percentOff }}%</span>
                            @endif
                        @endif
                    </div>

                    <ul class="list-unstyled product-meta mb-4">
                        <li><strong>{{ __('Référence') }} :</strong> #{{ $product->id }}</li>
                        @if ($productCategory)
                            <li>
                                <strong>{{ __('Catégorie') }} :</strong>
                                <a class="text-dark" href="{{ route('shop', $productCategory->id) }}">{{ $productCategory->name }}</a>
                            </li>
                        @endif
                        <li><strong>{{ __('Disponibilité') }} :</strong> {{ __('En stock') }}</li>
                    </ul>

                    <p class="mb-4">{{ \Illuminate\Support\Str::limit($product->description, 300) }}</p>

                    <div class="d-flex align-items-center mb-4 pt-2">
                        <div class="input-group quantity mr-3" style="width: 130px;">
                            <div class="input-group-btn">
                                <button class="btn btn-primary btn-minus">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control bg-secondary border-0 text-center" value="1" id="itemCount_{{ $product->id }}">
                            <div class="input-group-btn">
                                <button class="btn btn-primary btn-plus">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn btn-primary px-3" onclick="addToCart({{ $product->id }})">
                            <i class="fa fa-shopping-cart mr-1"></i> {{ __("Ajouter au panier") }}
                        </button>
                    </div>

                    <div class="border-top pt-3">
                        <p class="mb-2"><i class="fa fa-shipping-fast text-primary mr-2"></i>{{ __('Livraison à Cotonou et partout en Afrique.') }}</p>
                        <p class="mb-2"><i class="fa fa-lock text-primary mr-2"></i>{{ __('Paiement mobile money ou carte bancaire.') }}</p>
                        <p class="mb-0">
                            <i class="fa fa-phone-alt text-primary mr-2"></i>{{ __('Une question ?') }}
                            <a class="text-dark" href="tel:{{ env('STORE_OWNER_PHONE_NUMBER') }}">{{ env('STORE_OWNER_PHONE_NUMBER') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row px-xl-5">
            <div class="col">
                <div class="bg-light p-30">
                    <div class="nav nav-tabs mb-4">
                        <a class="nav-item nav-link text-dark active" data-toggle="tab" href="#tab-pane-1">{{ __('Description') }}</a>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-pane-1">
                            <h4 class="mb-3">{{ __('Description du produit') }}</h4>
                            <p class="mb-0">{{ $product->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fiche produit End -->


    <!-- Produits similaires Start -->
    @if ($relatedProducts->count())
        <div class="container-fluid py-5">
            <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
                <span class="bg-secondary pr-3">{{ __('Produits similaires') }}</span>
            </h2>
            <div class="row px-xl-5">
                @foreach ($relatedProducts as $relatedProduct)
                    @include('partials.product-card', ['product' => $relatedProduct])
                @endforeach
            </div>
        </div>
    @endif
    <!-- Produits similaires End -->

@endsection

@extends('layouts.shop')

@section('page_title')
    {{ env('SHOP_NAME') }} Sarl - {{ __('Vente de matériel médical') }}
@endsection

@section('content')

    <!-- Présentation Start -->
    <div class="container-fluid pt-4 pb-2">
        <div class="row px-xl-5">
            <div class="col-12">
                <h1 class="h5 mb-2">{{ env('SHOP_NAME') }} Sarl &mdash; {{ __('vente de matériel et de consommables médicaux') }}</h1>
                <p class="mb-0">
                    {{ __('Nous fournissons les cliniques, cabinets, pharmacies et particuliers : mobilier de soin, instruments, consommables et médicaments. Commande en ligne ou par téléphone, livraison à Cotonou et partout en Afrique.') }}
                </p>
            </div>
        </div>
    </div>
    <!-- Présentation End -->

    <!-- Bandeau Start -->
    @php
        $heroProducts = $products->sortByDesc('created_at')->take(3)->values();
        $promoProduct = $products->firstWhere('discount_price', '!=', -1);
        $newProduct   = $products->sortByDesc('created_at')->first();
    @endphp
    <div class="container-fluid mb-3">
        <div class="row px-xl-5">
            <div class="col-lg-8">
                <div id="header-carousel" class="carousel slide carousel-fade mb-30 mb-lg-0" data-ride="carousel">
                    @if ($heroProducts->count())
                        <ol class="carousel-indicators">
                            @foreach ($heroProducts as $index => $heroProduct)
                                <li data-target="#header-carousel" data-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
                            @endforeach
                        </ol>
                    @endif
                    <div class="carousel-inner">
                        @forelse ($heroProducts as $index => $heroProduct)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img class="img-fluid" src="{{ asset('storage/' . image($heroProduct)) }}" alt="{{ $heroProduct->name }}">
                                <div class="carousel-caption d-flex flex-column align-items-start justify-content-center text-left">
                                    <div class="p-3 p-md-5" style="max-width: 560px;">
                                        <h6 class="text-primary text-uppercase mb-2 animate__animated animate__fadeInDown">
                                            {{ __('Notre sélection') }}
                                        </h6>
                                        <h2 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">
                                            {{ $heroProduct->name }}
                                        </h2>
                                        <h3 class="text-white mb-4 animate__animated animate__bounceIn">
                                            {{ number_format($heroProduct->discount_price != -1 ? $heroProduct->discount_price : $heroProduct->price, 0, ',', ' ') }} F
                                            @if ($heroProduct->discount_price != -1)
                                                <small class="text-secondary ml-2"><del>{{ number_format($heroProduct->price, 0, ',', ' ') }} F</del></small>
                                            @endif
                                        </h3>
                                        <a class="btn btn-primary py-2 px-4 animate__animated animate__fadeInUp" href="{{ route('product.view', $heroProduct->id) }}">
                                            {{ __('Voir le produit') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <img class="img-fluid" src="{{ asset('img/carousel-1.jpg') }}" alt="{{ env('SHOP_NAME') }}">
                                <div class="carousel-caption d-flex flex-column align-items-start justify-content-center text-left">
                                    <div class="p-3 p-md-5" style="max-width: 560px;">
                                        <h2 class="display-4 text-white mb-3">{{ __('Bienvenue chez') }} {{ env('SHOP_NAME') }}</h2>
                                        <p class="text-white">{{ env('APP_DESCRIPTION', 'Des produits de qualité, livrés près de chez vous.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    @if ($heroProducts->count() > 1)
                        <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
                            <div class="btn btn-dark" style="width: 45px; height: 45px;">
                                <span class="carousel-control-prev-icon mb-n2"></span>
                            </div>
                        </a>
                        <a class="carousel-control-next" href="#header-carousel" data-slide="next">
                            <div class="btn btn-dark" style="width: 45px; height: 45px;">
                                <span class="carousel-control-next-icon mb-n2"></span>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-lg-4">
                <div class="product-offer mb-30" style="height: 200px;">
                    <img class="img-fluid" src="{{ $promoProduct ? asset('storage/' . image($promoProduct)) : asset('img/offer-1.jpg') }}" alt="{{ __('Promotions') }}">
                    <div class="offer-text">
                        <h6 class="text-primary text-uppercase mb-1">
                            @if ($promoProduct && $promoProduct->price > 0)
                                {{ __('Bons plans') }} &mdash; -{{ (int) round(100 - ($promoProduct->discount_price * 100 / $promoProduct->price)) }}%
                            @else
                                {{ __('Bons plans') }}
                            @endif
                        </h6>
                        <h3 class="text-white mb-3 text-center px-3">
                            {{ $promoProduct ? $promoProduct->name : __('Produits en promotion') }}
                        </h3>
                        <a href="{{ $promoProduct ? route('product.view', $promoProduct->id) : route('shop') }}" class="btn btn-primary">
                            {{ __('En profiter') }}
                        </a>
                    </div>
                </div>
                <div class="product-offer mb-30" style="height: 200px;">
                    <img class="img-fluid" src="{{ $newProduct ? asset('storage/' . image($newProduct)) : asset('img/offer-2.jpg') }}" alt="{{ __('Nouveautés') }}">
                    <div class="offer-text">
                        <h6 class="text-primary text-uppercase mb-1">{{ __('Nouveautés') }}</h6>
                        <h3 class="text-white mb-3 text-center px-3">{{ __('Nos derniers arrivages') }}</h3>
                        <a href="{{ route('shop') }}" class="btn btn-primary">{{ __('Découvrir') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bandeau End -->


    <!-- Avantages Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5 pb-3">
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fa fa-check text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">{{ __('Produits de qualité') }}</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fa fa-shipping-fast text-primary m-0 mr-2"></h1>
                    <h5 class="font-weight-semi-bold m-0">{{ __('Livraison à domicile') }}</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fas fa-lock text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">{{ __('Paiement sécurisé') }}</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fa fa-phone-volume text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">{{ __('Service client 24/7') }}</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- Avantages End -->


    <!-- Catégories Start -->
    <div class="container-fluid pt-5" id="categories">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
            <span class="bg-secondary pr-3">{{ __('Catégories') }}</span>
        </h2>
        <div class="row px-xl-5 pb-3">
            @forelse ($categories as $category)
                <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                    <a class="text-decoration-none" href="{{ route('shop', $category->id) }}">
                        <div class="cat-item d-flex align-items-center mb-4">
                            <div class="overflow-hidden" style="width: 100px; height: 100px;">
                                <img class="img-fluid w-100 h-100" style="object-fit: cover;" src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                            </div>
                            <div class="flex-fill pl-3">
                                <h6 class="mb-1">{{ $category->name }}</h6>
                                <small class="text-body">
                                    {{ $category->products()->count() }}
                                    {{ $category->products()->count() > 1 ? __('produits') : __('produit') }}
                                </small>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 pb-4">
                    <p class="text-muted mb-0">{{ __('Aucune catégorie pour le moment.') }}</p>
                </div>
            @endforelse
        </div>
    </div>
    <!-- Catégories End -->


    <!-- Produits Start -->
    <div class="container-fluid pt-5 pb-3" id="produits">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
            <span class="bg-secondary pr-3">{{ __('Nos produits') }}</span>
        </h2>
        <div class="row px-xl-5">
            @forelse ($products->sortByDesc('created_at')->take(8) as $product)
                @include('partials.product-card', ['product' => $product])
            @empty
                <div class="col-12 pb-4">
                    <p class="text-muted mb-0">{{ __('Aucun produit disponible pour le moment.') }}</p>
                </div>
            @endforelse
        </div>
        @if ($products->count() > 8)
            <div class="row px-xl-5">
                <div class="col-12 text-center pt-3">
                    <a class="btn btn-primary px-4 py-2" href="{{ route('shop') }}">{{ __('Voir tous les produits') }}</a>
                </div>
            </div>
        @endif
    </div>
    <!-- Produits End -->


    <!-- Contact Start -->
    <div class="container-fluid pb-5">
        <div class="row px-xl-5">
            <div class="col-12">
                <div class="bg-dark p-5 text-center">
                    <h3 class="text-white mb-3">{{ __('Une question sur un produit ?') }}</h3>
                    <p class="text-secondary mb-4">
                        {{ __('Notre service client est disponible pour vous conseiller, établir un devis et suivre votre commande.') }}
                    </p>
                    <a class="btn btn-primary px-4 py-2" href="tel:{{ env('STORE_OWNER_PHONE_NUMBER') }}">
                        <i class="fa fa-phone-alt mr-2"></i>{{ env('STORE_OWNER_PHONE_NUMBER') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->

@endsection

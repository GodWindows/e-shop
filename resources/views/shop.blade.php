@extends('layouts.shop')

@section('page_title')
    {{ $currentCategory ? $currentCategory->name : __('Tous nos produits') }} - {{ env('SHOP_NAME') }}
@endsection

@section('content')

    <!-- Fil d'ariane Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{ route('welcome') }}">{{ __('Accueil') }}</a>
                    <a class="breadcrumb-item text-dark" href="{{ route('shop') }}">{{ __('Produits') }}</a>
                    @if ($currentCategory)
                        <span class="breadcrumb-item active">{{ $currentCategory->name }}</span>
                    @endif
                </nav>
            </div>
        </div>
    </div>
    <!-- Fil d'ariane End -->

    <!-- Boutique Start -->
    <div class="container-fluid pb-5">
        <div class="row px-xl-5">

            <!-- Colonne catégories -->
            <div class="col-lg-3 col-md-4">
                <div class="bg-light p-30 mb-30">
                    <h5 class="font-weight-semi-bold mb-3">{{ __('Catégories') }}</h5>
                    <div class="list-group category-list">
                        <a href="{{ route('shop') }}" class="list-group-item d-flex justify-content-between align-items-center {{ $currentCategory ? '' : 'active' }}">
                            {{ __('Tous les produits') }}
                        </a>
                        @foreach ($categories as $category)
                            <a href="{{ route('shop', $category->id) }}" class="list-group-item d-flex justify-content-between align-items-center {{ $currentCategory && $currentCategory->id === $category->id ? 'active' : '' }}">
                                {{ $category->name }}
                                <span class="text-muted small">{{ $category->products()->count() }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="bg-light p-30 mb-30">
                    <h5 class="font-weight-semi-bold mb-3">{{ __('Besoin d\'aide ?') }}</h5>
                    <p class="mb-2">{{ __('Appelez-nous pour un conseil ou un devis :') }}</p>
                    <a class="h5 text-dark" href="tel:{{ env('STORE_OWNER_PHONE_NUMBER') }}">{{ env('STORE_OWNER_PHONE_NUMBER') }}</a>
                </div>
            </div>

            <!-- Grille produits -->
            <div class="col-lg-9 col-md-8">
                <div class="row pb-3">
                    <div class="col-12 pb-1 d-flex flex-wrap align-items-center justify-content-between">
                        <h4 class="mb-0">{{ $currentCategory ? $currentCategory->name : __('Tous nos produits') }}</h4>
                        <span class="text-muted">
                            {{ $products->count() }} {{ $products->count() > 1 ? __('produits') : __('produit') }}
                        </span>
                    </div>
                </div>
                <div class="row">
                    @forelse ($products as $product)
                        @include('partials.product-card', ['product' => $product, 'cardCols' => 'col-lg-4 col-md-6 col-6'])
                    @empty
                        <div class="col-12">
                            <div class="bg-light p-30">
                                <p class="mb-3">{{ __('Aucun produit dans cette catégorie pour le moment.') }}</p>
                                <a class="btn btn-primary px-4" href="{{ route('shop') }}">{{ __('Voir tous les produits') }}</a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
    <!-- Boutique End -->

@endsection

@extends('layouts.shop')

    @section('page_title')
        {{ env('SHOP_NAME') }} Sarl
    @endsection

    @section('content')


        <!-- Featured Start -->
        <div class="container-fluid pt-5">
            <div class="row px-xl-5 pb-3">
                <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                    <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                        <h1 class="fa fa-check text-primary m-0 mr-3"></h1>
                        <h5 class="font-weight-semi-bold m-0">Quality Product</h5>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                    <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                        <h1 class="fa fa-shipping-fast text-primary m-0 mr-2"></h1>
                        <h5 class="font-weight-semi-bold m-0">Free Shipping</h5>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                    <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                        <h1 class="fas fa-exchange-alt text-primary m-0 mr-3"></h1>
                        <h5 class="font-weight-semi-bold m-0">14-Day Return</h5>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                    <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                        <h1 class="fa fa-phone-volume text-primary m-0 mr-3"></h1>
                        <h5 class="font-weight-semi-bold m-0">24/7 Support</h5>
                    </div>
                </div>
            </div>
        </div>
        <!-- Featured End -->


        <!-- Categories Start -->
        <div class="container-fluid pt-5">
            <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Catégories</span></h2>
            <div class="row px-xl-5 pb-3">
                @foreach ($categories as $category)
                    <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                        <a class="text-decoration-none" href="#" onClick="return false;">
                            <div class="cat-item d-flex align-items-center mb-4">
                                <div class="overflow-hidden" style="width: 100px; height: 100px;">
                                    <img class="img-fluid" src="{{asset('storage/' . $category->image )}}" alt="">
                                </div>
                                <div class="flex-fill pl-3">
                                    <h6>{{ $category->name }}</h6>
                                    <small class="text-body"> {{ $category->products()->count() }}
                                        @if ( $category->products()->count() > 1)
                                            produits
                                        @else
                                            produit
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

        <!-- Categories End -->


        <!-- Products Start -->
        <div class="container-fluid pt-5 pb-3">
            <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Featured Products</span></h2>
            <div class="row px-xl-5">
                @foreach ($lastFourProducts as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                        <div class="product-item bg-light mb-4">
                            <div class="product-img position-relative overflow-hidden" >
                                <img class="img-fluid w-100 cursor-pointer" src="{{asset('storage/'.image($product))}}" alt="Product Image" >
                                    <div class="product-action" >
                                        <a class="btn btn-outline-dark btn-square" onclick="addToCart({{$product->id}})"><i class="fa fa-shopping-cart"></i></a>
                                    </div>
                            </div>
                            <div class="text-center py-4">
                                <a class="h6 text-decoration-none text-truncate" href="{{route('product.view',$product->id)}}">{{ $product->name }}</a>
                                <div class="d-flex align-items-center justify-content-center mt-2">
                                    @if ($product->discount_price == -1)
                                        <h5>{{ $product->price }} F</h5>
                                    @else
                                        <h5>{{ $product->discount_price }} F</h5><h6 class="text-muted ml-2"><del>{{ $product->price }} F</del></h6>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- Products End -->


       


    @endsection

    @section('scripts')
        @include('javascript.function')
    @endsection

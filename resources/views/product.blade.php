@extends('layouts.shop')

@section('page_title')
    Détails du produit
@endsection

@section('content')
     <!-- Breadcrumb Start -->
     <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{route('cart')}}">{{ ("Aller au panier") }}</a>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->


    <!-- Shop Detail Start -->
    <div class="container-fluid pb-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 mb-30">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner bg-light">
                        @foreach (removeEmptyValuesFromArray(json_decode($product->images)) as $image)
                            <div @if ($image == json_decode($product->images)[0])
                                class="carousel-item active"
                            @else
                                class="carousel-item"
                            @endif>
                                <img class="w-100 h-100" src="{{asset('storage/'.$image)}}" alt="Image">
                            </div>
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-7 h-auto mb-30">
                <div class="h-100 bg-light p-30">
                    <h3>{{$product->name}}</h3>
                    <h3 class="font-weight-semi-bold mb-4">
                        @if ($product->discount_price == -1)
                            {{ $product->price }} F
                        @else
                            <h5>
                                {{ $product->discount_price }} F
                            </h5>
                            <h6 class="text-muted ml-2">
                                <del>{{ $product->price }} F</del>
                            </h6>
                        @endif
                    </h3>
                    <p class="mb-4">
                        {{$product->description}}
                    </p>
                    <div class="d-flex align-items-center mb-4 pt-2">
                        <div class="input-group quantity mr-3" style="width: 130px;">
                            <div class="input-group-btn">
                                <button class="btn btn-primary btn-minus">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control bg-secondary border-0 text-center" value="1" id=itemCount>
                            <div class="input-group-btn">
                                <button class="btn btn-primary btn-plus">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn btn-primary px-3" onclick="addToCart({{$product->id}})"><i class="fa fa-shopping-cart mr-1"></i> {{ __("Ajouter au panier") }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row px-xl-5">
            <div class="col">
                <div class="bg-light p-30">
                    <div class="nav nav-tabs mb-4">
                        <a class="nav-item nav-link text-dark active" data-toggle="tab" href="#tab-pane-1">Description</a>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-pane-1">
                            <h4 class="mb-3">Description du produit</h4>
                            {{$product->description}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Detail End -->


    <!-- Products Start -->
    <div class="container-fluid py-5">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Produits qui pourraient vous intéresser</span></h2>
        <div class="row px-xl-5">
            @foreach ($relatedProducts as $product )
                <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                    <div class="product-item bg-light mb-4 cursor-pointer" onclick="window.location='{{route('product.view',$product->id)}}'">
                        
                        <!-- Updated Image Container -->
                        <div class="product-img-fixed">
                            <img class="cursor-pointer" src="{{asset('storage/'.image($product))}}" alt="Product Image"  >
                        </div>
                        <!-- End Updated Image Container -->

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
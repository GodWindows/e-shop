@extends('layouts.shop')

@section('page_title')
    Panier
@endsection

@section('content')
<!-- Breadcrumb Start -->
<div class="container-fluid">
   <div class="row px-xl-5">
       <div class="col-12">
           <nav class="breadcrumb bg-light mb-30">
               <a class="breadcrumb-item text-dark" href="{{route('welcome')}}">{{ ("Cliquez ici pour retourner à la boutique") }}</a>
           </nav>
       </div>
   </div>
</div>
<!-- Breadcrumb End -->

    @php
    
        if (isset($_COOKIE['cart'])) {
            $cookieData = $_COOKIE["cart"]; 
            $cookieData = json_decode($cookieData, true);
            $cartArray= array();
            foreach ($cookieData as $row) {
                $cartArray[$row[0]] = $row[1];
            }
        }else {
            $cartArray = array();
            echo "<h3>Votre panier est vide</h3>";
        }
    @endphp
    @foreach ($cartArray as $id => $amount)

        @php
            $product = App\Models\Product::find($id);
        @endphp

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
                                    
                                    <!-- I removed the w-100 and h-100 classes from here -->
                                    <img src="{{asset('storage/'.$image)}}" alt="Image">

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
                        <div class="d-flex align-items-center mb-4 pt-2">
                            <div class="input-group quantity mr-3" style="width: 130px;">
                                <div class="input-group-btn" onclick="editAmountInCart({{$product->id}})">
                                    <button class="btn btn-primary btn-minus">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control bg-secondary border-0 text-center" value="{{ $amount }}" id="itemCount_{{ $product->id  }}">
                                <div class="input-group-btn">
                                    <button class="btn btn-primary btn-plus" onclick="editAmountInCart({{$product->id}})">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <button class="btn btn-primary px-3" onclick="deleteFromCart({{$product->id}})"><i class="fa fa-shopping-cart mr-1"></i> {{ __("Supprimer du panier") }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
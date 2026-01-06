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
        $emptyCart = true;
    
        if (isset($_COOKIE['cart'])) {
            $cookieData = $_COOKIE["cart"]; 
            $cookieData = json_decode($cookieData, true);
            $cartArray= array();
            foreach ($cookieData as $row) {
                $cartArray[$row[0]] = $row[1];
            }
            $cartTotalAmount = 0;
            $emptyCart = false;
        }else {
            $cartArray = array();
            echo "<h3>Votre panier est vide</h3>";
        }
    @endphp
    @foreach ($cartArray as $id => $amount)
        
        @php
            $product = App\Models\Product::find($id);
            $cartTotalAmount += ($product->discount_price == -1 ? $product->price : $product->discount_price) * $amount;
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
    <button id="pay-btn" class="pay-btn-custom">Passer au paiement</button>

    <!-- Payment Info Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Informations Requises</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">Veuillez entrer vos informations pour continuer.</p>
                    <form>
                        <div class="form-group">
                            <label for="userName">Nom & Prénom</label>
                            <input type="text" class="form-control" id="userName" placeholder="Votre nom complet" required>
                        </div>
                        <div class="form-group">
                            <label for="userPhone">Téléphone</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <select class="custom-select" id="countryCode" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                        <option value="+229" selected>+229 (Bénin)</option>
                                        <option value="+228">+228 (Togo)</option>
                                        <option value="+225">+225 (CI)</option>
                                        <option value="+221">+221 (Sénégal)</option>
                                        <option value="+33">+33 (France)</option>
                                        <option value="+1">+1 (USA)</option>
                                    </select>
                                </div>
                                <input type="tel" class="form-control" id="userPhone" placeholder="Ex: 97000000" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="confirmPayment">Validé et Payer</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    @if (!$emptyCart)

        <script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>
    
        <script type="text/javascript">
            let widget =  FedaPay.init({
                public_key: 'pk_sandbox_1BC-3mOEqiAXxg5EWRe0Wude',
                transaction: {
                    amount: {{ $cartTotalAmount }} ,
                    description: 'Acheter mon produit',
                    custom_metadata:{
                        foo: 'bar'
                    }
                },
            });
            
            let btn = document.getElementById('pay-btn');
            
            // Intercept click to show modal
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                $('#paymentModal').modal('show');
            });

            // Handle Modal Confirmation
            document.getElementById('confirmPayment').addEventListener('click', () => {
                let name = document.getElementById('userName').value.trim();
                let phone = document.getElementById('userPhone').value.trim();
                let countryCode = document.getElementById('countryCode').value;

                if (name === "" || phone === "") {
                    // Simple validation feedback (can be improved with Bootstrap validation styles)
                    alert("Veuillez remplir le nom et le numéro de téléphone.");
                    return;
                }

                // Close modal
                $('#paymentModal').modal('hide');

                // Trigger Payment
                // Ideally, we might want to update the widget custom metadata or customer info here
                // But simply opening it as requested:
                widget.open();
            });
        </script>
        
    @endif
@endsection

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
        $cartTotalAmount = 0;
    
        if (isset($_COOKIE['cart'])) {
            $cookieData = $_COOKIE["cart"]; 
            $cookieData = json_decode($cookieData, true);
            $cartArray= array();
            foreach ($cookieData as $row) {
                $cartArray[$row[0]] = $row[1];
            }
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

        <div class="container-fluid pb-5" id="product-row-{{$product->id}}">
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
                                <div class="input-group-btn">
                                    <button class="btn btn-primary btn-minus">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control bg-secondary border-0 text-center" value="{{ $amount }}" id="itemCount_{{ $product->id  }}" data-price="{{ $product->discount_price == -1 ? $product->price : $product->discount_price }}" onchange="editAmountInCart({{$product->id}})">
                                <div class="input-group-btn">
                                    <button class="btn btn-primary btn-plus">
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
    <div class="row px-xl-5">
        <div class="col-lg-8"></div>
        <div class="col-lg-4">
            <div class="bg-light p-30 mb-5">
                <div class="border-bottom pb-2">
                    <div class="d-flex justify-content-between mb-3">
                        <h6>Sous-total</h6>
                        <h6><span id="cart-subtotal">{{ $cartTotalAmount }}</span> F</h6>
                    </div>
                </div>
                <div class="pt-2">
                    <div class="d-flex justify-content-between mt-2">
                        <h5>Total</h5>
                        <h5><span id="cart-total">{{ $cartTotalAmount }}</span> F</h5>
                    </div>
                    <button id="pay-btn" class="btn btn-block btn-primary font-weight-bold my-3 py-3">Passer au paiement</button>
                </div>
            </div>
        </div>
    </div>

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
                    <p class="small text-muted mb-3">Veuillez entrer vos informations pour continuer. Ces informations seront utilisées pour traiter votre commande.</p>
                    <form>
                        <div class="form-group">
                            <label for="userName">Nom & Prénom</label>
                            <input type="text" class="form-control" id="userName" placeholder="Votre nom complet" required>
                        </div>
                        <div class="form-group">
                            <label for="userPhone">Téléphone</label>
                            <input type="tel" class="form-control" id="userPhone" placeholder="Ex: 97000000" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="confirmPayment">Valider et Payer</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    @if (!$emptyCart)

        <script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>
    
        <script type="text/javascript">
            // Function to calculate total dynamically from DOM
            function getCartTotal() {
                let total = 0;
                // Select all quantity inputs
                const inputs = document.querySelectorAll('input[id^="itemCount_"]');
                inputs.forEach(input => {
                    const price = parseFloat(input.dataset.price);
                    const quantity = parseInt(input.value);
                    if (!isNaN(price) && !isNaN(quantity)) {
                        total += price * quantity;
                    }
                });
                return total;
            }

            // Function to get all cart items details
            function getCartItems() {
                let items = [];
                const inputs = document.querySelectorAll('input[id^="itemCount_"]');
                inputs.forEach(input => {
                    const id = input.id.replace('itemCount_', '');
                    const price = parseFloat(input.dataset.price);
                    const quantity = parseInt(input.value);
                    if (!isNaN(price) && !isNaN(quantity)) {
                        items.push({
                            product_id: id,
                            quantity: quantity,
                            price: price
                        });
                    }
                });
                return JSON.stringify(items);
            }

            // Global function to update the display
            window.updateCartTotalDisplay = function() {
                const total = getCartTotal();
                const totalElement = document.getElementById('cart-total');
                const subTotalElement = document.getElementById('cart-subtotal');
                
                if (totalElement) totalElement.innerText = total;
                if (subTotalElement) subTotalElement.innerText = total;
            };

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

                if (name === "" || phone === "") {
                    // Simple validation feedback
                    alert("Veuillez remplir le nom et le numéro de téléphone.");
                    return;
                }

                // Close modal
                $('#paymentModal').modal('hide');

                // Dynamic Total Calculation
                let currentTotal = getCartTotal();
                let cartItems = getCartItems();
                
                // Initialize FedaPay with dynamic amount and custom metadata
                let widget = FedaPay.init({
                    //public_key: 'pk_sandbox_1BC-3mOEqiAXxg5EWRe0Wude',
                    public_key: 'pk_live_sdCP6lZsiuy1mzykeI1_we5l',
                    transaction: {
                        amount: currentTotal,
                        description: 'Acheter mon produit',
                        custom_metadata: {
                            customer_name: name,
                            customer_phone: phone,
                            cart_items: cartItems
                        }
                    },
                    onComplete: function(response) {
                        if (response.reason === FedaPay.CHECKOUT_COMPLETED) {
                            // Payment successful - clear cart and redirect to thank you page
                            document.cookie = "cart=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                            let transactionRef = response.transaction ? response.transaction.reference : '';
                            window.location.href = "{{ route('thankyou') }}?ref=" + encodeURIComponent(transactionRef);
                        } else if (response.reason === FedaPay.DIALOG_DISMISSED) {
                            // User closed the dialog without completing payment
                            console.log('Paiement annulé par l\'utilisateur');
                        }
                    }
                });

                // Open widget
                widget.open();
            });
        </script>
        
    @endif
@endsection

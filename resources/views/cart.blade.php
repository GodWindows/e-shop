@extends('layouts.shop')

@section('page_title')
    {{ __('Mon panier') }} - {{ env('SHOP_NAME') }}
@endsection

@section('content')

    @php
        $cartItems = array();
        $cartTotalAmount = 0;

        if (isset($_COOKIE['cart'])) {
            $cookieData = json_decode($_COOKIE['cart'], true) ?: array();
            foreach ($cookieData as $row) {
                $product = App\Models\Product::find($row[0]);
                $amount = (int) $row[1];

                // Le produit a pu être supprimé du catalogue depuis l'ajout au panier
                if (!$product || $amount < 1) {
                    continue;
                }

                $unitPrice = $product->discount_price == -1 ? $product->price : $product->discount_price;
                $cartItems[] = array(
                    'product' => $product,
                    'amount' => $amount,
                    'unitPrice' => $unitPrice,
                    'lineTotal' => $unitPrice * $amount,
                );
                $cartTotalAmount += $unitPrice * $amount;
            }
        }

        $emptyCart = count($cartItems) === 0;
    @endphp

    <!-- Fil d'ariane Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{ route('welcome') }}">{{ __('Accueil') }}</a>
                    <a class="breadcrumb-item text-dark" href="{{ route('shop') }}">{{ __('Produits') }}</a>
                    <span class="breadcrumb-item active">{{ __('Panier') }}</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Fil d'ariane End -->

    <!-- Panier Start -->
    <div class="container-fluid pb-5">
        @if ($emptyCart)
            <div class="row px-xl-5">
                <div class="col-12">
                    <div class="bg-light p-30 text-center">
                        <h4 class="mb-3">{{ __('Votre panier est vide') }}</h4>
                        <p class="mb-4">{{ __('Parcourez notre catalogue et ajoutez les produits dont vous avez besoin.') }}</p>
                        <a class="btn btn-primary px-4 py-2" href="{{ route('shop') }}">{{ __('Voir nos produits') }}</a>
                    </div>
                </div>
            </div>
        @else
            <div class="row px-xl-5">
                <div class="col-lg-8 table-responsive mb-5">
                    <table class="table table-bordered text-center mb-0 bg-light cart-table">
                        <thead class="bg-secondary text-dark">
                            <tr>
                                <th class="text-left">{{ __('Produit') }}</th>
                                <th class="d-none d-sm-table-cell">{{ __('Prix') }}</th>
                                <th>{{ __('Quantité') }}</th>
                                <th>{{ __('Total') }}</th>
                                <th>{{ __('Retirer') }}</th>
                            </tr>
                        </thead>
                        <tbody class="align-middle">
                            @foreach ($cartItems as $item)
                                @php
                                    $product = $item['product'];
                                @endphp
                                <tr id="product-row-{{ $product->id }}">
                                    <td class="text-left">
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('product.view', $product->id) }}">
                                                <img class="cart-thumb mr-3" src="{{ asset('storage/' . image($product)) }}" alt="{{ $product->name }}">
                                            </a>
                                            <a class="text-dark" href="{{ route('product.view', $product->id) }}">{{ $product->name }}</a>
                                        </div>
                                    </td>
                                    <td class="d-none d-sm-table-cell">{{ number_format($item['unitPrice'], 0, ',', ' ') }} F</td>
                                    <td>
                                        <div class="input-group quantity mx-auto" style="width: 130px;">
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-primary btn-minus">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="text" class="form-control form-control-sm bg-secondary border-0 text-center"
                                                   value="{{ $item['amount'] }}"
                                                   id="itemCount_{{ $product->id }}"
                                                   data-price="{{ $item['unitPrice'] }}"
                                                   onchange="editAmountInCart({{ $product->id }})">
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-primary btn-plus">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span id="line-total-{{ $product->id }}">{{ number_format($item['lineTotal'], 0, ',', ' ') }}</span> F</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="deleteFromCart({{ $product->id }})" title="{{ __('Retirer du panier') }}">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex flex-wrap justify-content-between mt-4">
                        <a class="btn btn-outline-dark px-4" href="{{ route('shop') }}">
                            <i class="fa fa-angle-left mr-2"></i>{{ __('Continuer mes achats') }}
                        </a>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="bg-light p-30 mb-5">
                        <h5 class="font-weight-semi-bold mb-4">{{ __('Récapitulatif') }}</h5>
                        <div class="border-bottom pb-2">
                            <div class="d-flex justify-content-between mb-3">
                                <h6>{{ __('Sous-total') }}</h6>
                                <h6><span id="cart-subtotal">{{ number_format($cartTotalAmount, 0, ',', ' ') }}</span> F</h6>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h6 class="font-weight-normal">{{ __('Livraison') }}</h6>
                                <h6 class="font-weight-normal text-muted">{{ __('À convenir') }}</h6>
                            </div>
                        </div>
                        <div class="pt-2">
                            <div class="d-flex justify-content-between mt-2">
                                <h5>{{ __('Total') }}</h5>
                                <h5><span id="cart-total">{{ number_format($cartTotalAmount, 0, ',', ' ') }}</span> F</h5>
                            </div>
                            <button id="pay-btn" class="btn btn-block btn-primary font-weight-bold my-3 py-3">{{ __('Passer au paiement') }}</button>
                            <p class="small text-muted mb-0">
                                {{ __('Les frais de livraison sont confirmés par téléphone après la commande.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <!-- Panier End -->

    <!-- Informations de commande -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">{{ __('Informations requises') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Fermer') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">{{ __('Veuillez entrer vos informations pour continuer. Elles seront utilisées pour traiter votre commande.') }}</p>
                    <form>
                        <div class="form-group">
                            <label for="userName">{{ __('Nom & prénom') }}</label>
                            <input type="text" class="form-control" id="userName" placeholder="{{ __('Votre nom complet') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="userPhone">{{ __('Téléphone') }}</label>
                            <input type="tel" class="form-control" id="userPhone" placeholder="Ex: 97000000" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Annuler') }}</button>
                    <button type="button" class="btn btn-primary" id="confirmPayment">{{ __('Valider et payer') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    @if (!$emptyCart)

        <script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>

        <script type="text/javascript">
            // Formatage des montants : 100000 -> 100 000
            function formatAmount(value) {
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            }

            // Total calculé à partir des quantités affichées
            function getCartTotal() {
                let total = 0;
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

            // Détail du panier envoyé au paiement
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

            // Mise à jour des totaux (ligne par ligne + récapitulatif)
            window.updateCartTotalDisplay = function() {
                const inputs = document.querySelectorAll('input[id^="itemCount_"]');

                // Plus aucune ligne : on recharge pour afficher le panier vide
                if (inputs.length === 0) {
                    window.location.reload();
                    return;
                }

                inputs.forEach(input => {
                    const id = input.id.replace('itemCount_', '');
                    const price = parseFloat(input.dataset.price);
                    const quantity = parseInt(input.value);
                    const lineElement = document.getElementById('line-total-' + id);
                    if (lineElement && !isNaN(price) && !isNaN(quantity)) {
                        lineElement.innerText = formatAmount(price * quantity);
                    }
                });

                const total = getCartTotal();
                const totalElement = document.getElementById('cart-total');
                const subTotalElement = document.getElementById('cart-subtotal');

                if (totalElement) totalElement.innerText = formatAmount(total);
                if (subTotalElement) subTotalElement.innerText = formatAmount(total);
            };

            let btn = document.getElementById('pay-btn');

            // On demande les coordonnées avant d'ouvrir le paiement
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                $('#paymentModal').modal('show');
            });

            document.getElementById('confirmPayment').addEventListener('click', () => {
                let name = document.getElementById('userName').value.trim();
                let phone = document.getElementById('userPhone').value.trim();

                if (name === "" || phone === "") {
                    alert("Veuillez remplir le nom et le numéro de téléphone.");
                    return;
                }

                $('#paymentModal').modal('hide');

                let currentTotal = getCartTotal();
                let cartItems = getCartItems();

                let widget = FedaPay.init({
                    public_key: '{{ env("FEDAPAY_PUBLIC_KEY") }}',
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
                            // Paiement validé : on vide le panier et on redirige
                            document.cookie = "cart=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                            let transactionRef = response.transaction ? response.transaction.reference : '';
                            window.location.href = "{{ route('thankyou') }}?ref=" + encodeURIComponent(transactionRef);
                        } else if (response.reason === FedaPay.DIALOG_DISMISSED) {
                            console.log('Paiement annulé par l\'utilisateur');
                        }
                    }
                });

                widget.open();
            });
        </script>

    @endif
@endsection

@extends('layouts.shop')

@section('page_title')
    Merci pour votre commande
@endsection

@section('content')
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <div class="col-12">
                <div class="bg-light p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                    </div>
                    <h1 class="display-4 text-primary mb-4">Merci pour votre commande !</h1>
                    <p class="lead mb-4">
                        Votre paiement a été effectué avec succès. 
                        Nous avons bien reçu votre commande et elle est en cours de préparation.
                    </p>
                    
                    @if(request('ref'))
                        <div class="mb-4">
                            <p class="text-muted">Numéro de commande :</p>
                            <h4 class="text-dark"><strong>{{ request('ref') }}</strong></h4>
                        </div>
                    @endif

                    <div class="alert alert-info d-inline-block mb-4">
                        <i class="fas fa-envelope mr-2"></i>
                        Un email de confirmation vous a été envoyé avec les détails de votre commande.
                    </div>

                    <p class="mb-4">
                        Nous vous contacterons bientôt pour organiser la livraison de vos articles.
                    </p>

                    <a href="{{ route('welcome') }}" class="btn btn-primary btn-lg px-5 py-3">
                        <i class="fas fa-home mr-2"></i> Retourner à la boutique
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

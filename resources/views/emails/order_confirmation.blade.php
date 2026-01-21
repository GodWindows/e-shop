<x-mail::message>
# ✅ Merci pour votre commande !

Bonjour **{{ $customerName }}**,

Nous avons bien reçu votre commande et elle est en cours de préparation.

---

## 📋 Récapitulatif de votre commande

**Numéro de commande:** {{ $transactionId }}

**Date:** {{ $orderDateTime }} *(Heure du Bénin, GMT+1)*

---

## 🛍️ Articles commandés

@foreach ($orderItems as $item)
- **{{ $item['product_name'] }}**
  - Quantité: {{ $item['quantity'] }}
  - Prix unitaire: {{ number_format($item['price'], 0, ',', ' ') }} FCFA
@endforeach

---

**💰 Montant Total:** {{ number_format($amount, 0, ',', ' ') }} FCFA

---

Nous vous contacterons bientôt pour la livraison de votre commande.

Merci pour votre confiance !

Cordialement,<br>
**L'équipe {{ config('app.name') }}**
</x-mail::message>

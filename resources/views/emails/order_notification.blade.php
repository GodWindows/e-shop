<x-mail::message>
# 🛒 Nouvelle Commande Reçue

Bonjour,

Une nouvelle commande vient d'être passée sur votre boutique. Voici les détails pour préparer la commande.

---

## 📋 Informations de la Commande

**ID Transaction (Base de données):** {{ $transactionId }}

**ID Transaction FedaPay:** {{ $fedapayTransactionId }}

**Date et Heure:** {{ $orderDateTime }} *(Heure du Bénin, GMT+1)*

**Montant Total:** {{ number_format($amount, 0, ',', ' ') }} FCFA

---

## 👤 Informations du Client

**Nom:** {{ $customerName }}

**Téléphone:** {{ $customerPhone }}

---

## 🛍️ Articles Commandés

@foreach ($orderItems as $item)
- **{{ $item['product_name'] }}** (ID: {{ $item['product_id'] }})
  - Quantité: {{ $item['quantity'] }}
  - Prix unitaire: {{ number_format($item['price'], 0, ',', ' ') }} FCFA
@endforeach

---

Merci de préparer cette commande dans les meilleurs délais.

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>

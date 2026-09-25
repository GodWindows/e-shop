<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Identité de la boutique
    |--------------------------------------------------------------------------
    |
    | Ces valeurs viennent du fichier .env, mais elles transitent volontairement
    | par la configuration : une fois « php artisan config:cache » exécuté,
    | Laravel ne lit plus le .env et env() renvoie null partout ailleurs.
    | Les vues et les contrôleurs doivent donc utiliser config('shop.*').
    |
    */

    'name' => env('SHOP_NAME', 'Boutique'),

    'tagline' => env('APP_DESCRIPTION', 'Vente de matériel et de consommables médicaux.'),

    'phone' => env('STORE_OWNER_PHONE_NUMBER'),

    // Adresse affichée aux clients ; à défaut, celle utilisée pour l'envoi des mails.
    'email' => env('SHOP_CONTACT_EMAIL', env('MAIL_USERNAME')),

    // Destinataire des notifications de commande.
    'manager_email' => env('SHOP_MANAGER_EMAIL'),

    /*
    |--------------------------------------------------------------------------
    | Paiement FedaPay
    |--------------------------------------------------------------------------
    */

    'fedapay' => [

        'public_key' => env('FEDAPAY_PUBLIC_KEY'),

        'secret_key' => env('FEDAPAY_SECRET_KEY', 'sk_sandbox_YOUR_KEY_HERE'),

        'environment' => env('FEDAPAY_ENVIRONMENT', 'sandbox'),

    ],

];

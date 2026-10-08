<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Destinataire des messages du formulaire de contact
    |--------------------------------------------------------------------------
    | Adresse qui reçoit chaque message. Se règle dans .env :
    |   CONTACT_RECIPIENT_ADDRESS=contact@alustock.test
    |   CONTACT_RECIPIENT_NAME="Équipe AluStock"
    */
    'to' => [
        'address' => env('CONTACT_RECIPIENT_ADDRESS', env('MAIL_FROM_ADDRESS', 'hello@example.com')),
        'name'    => env('CONTACT_RECIPIENT_NAME', 'AluStock'),
    ],

    /*
    | Copie envoyée à l'utilisateur pour confirmer la réception ("trace" côté visiteur).
    */
    'send_acknowledgement' => env('CONTACT_SEND_ACK', true),

    /*
    | Limite anti-spam : nombre de messages par minute et par IP.
    */
    'throttle_per_minute' => env('CONTACT_THROTTLE', 3),
];
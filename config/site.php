<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Site Configuration
    |--------------------------------------------------------------------------
    |
    | General site configuration including contact information and SEO settings.
    |
    */

    'name' => env('SITE_NAME', 'JDOutlet'),
    'contact_email' => env('CONTACT_EMAIL', 'info@jdoutlet.com'),
    
    'seo' => [
        'title' => 'JDOutlet - Materiali per Eventi',
        'description' => 'JDOutlet - Vendita materiali per eventi: audio professionale, arredi vintage, scenografie, specchi e molto altro. Qualità garantita per i tuoi eventi.',
        'keywords' => 'eventi, audio professionale, arredi vintage, scenografie, specchi, materiali eventi, noleggio attrezzature, event planning',
        'og_image' => env('APP_URL', 'http://localhost') . '/images/og-default.jpg',
    ],
];


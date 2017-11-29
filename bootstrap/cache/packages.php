<?php

return [
  'thomaswelton/laravel-gravatar' => [
    'providers' => [
      0 => 'Thomaswelton\\LaravelGravatar\\LaravelGravatarServiceProvider',
    ],
    'aliases' => [
      'Gravatar' => 'Thomaswelton\\LaravelGravatar\\Facades\\Gravatar',
    ],
  ],
  'barryvdh/laravel-dompdf' => [
    'providers' => [
      0 => 'Barryvdh\\DomPDF\\ServiceProvider',
    ],
    'aliases' => [
      'PDF' => 'Barryvdh\\DomPDF\\Facade',
    ],
  ],
  'darryldecode/cart' => [
    'providers' => [
      0 => 'Darryldecode\\Cart\\CartServiceProvider',
    ],
    'aliases' => [
      'Cart' => 'Darryldecode\\Cart\\Facades\\CartFacade',
    ],
  ],
  'laravelcollective/html' => [
    'providers' => [
      0 => 'Collective\\Html\\HtmlServiceProvider',
    ],
    'aliases' => [
      'Form' => 'Collective\\Html\\FormFacade',
      'Html' => 'Collective\\Html\\HtmlFacade',
    ],
  ],
];

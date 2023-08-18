<?php

return[
    /*
     * Cart page
     */
    'cart' => [
        'group' => '{{group}}',
        'name' => '{{name}}',
        'price' => '{{price}}',
        'price-description' => '{{price-description}}',
        'feature' => '<li>{{feature}}</li>',
        'subscription' => '{{subscription}}',
        'url' => '{{url}}',
    ],
    /*
     * This is for welcome mail content
     */
    'welcome_mail' => [
        'website_url' => '{{website_url}}',
        'name' => '{{name}}',
        'username' => '{{username}}',
        'password' => '{{password}}',
        'url' => '{{url}}',
    ],

    /*
     * This is for order mail content
     */
    'order_mail' => [
        'name' => '{{name}}',
        'downloadurl' => '{{downloadurl}}',
        'invoiceurl' => '{{invoiceurl}}',
        'product' => '{{product}}',
        'number' => '{{number}}',
        'expiry' => '{{expiry}}',
        'url' => '{{url}}',
        'serialkeyurl' => '{{serialkeyurl}}',
        'knowledge_base' => '{{knowledge_base}}',
    ],

    'cloud_order' => [
        'name' => '{{name}}',
        'downloadurl' => '{{downloadurl}}',
        'invoiceurl' => '{{invoiceurl}}',
        'product' => '{{product}}',
        'number' => '{{number}}',
        'expiry' => '{{expiry}}',
        'url' => '{{url}}',
        'serialkeyurl' => '{{serialkeyur}}',
        'knowledge_base' => '{{knowledge_base}}',
    ],

    /*
     * This is for invoice mail content
     */
    'invoice_mail' => [
        'name' => '{{name}}',
        'number' => '{{number}}',
        'address' => '{{address}}',
        'invoiceurl' => '{{invoiceurl}}',
        'total' => '{{total}}',
        'content' => '{{content}}',
        'currency' => '{{currency}}',
    ],

    /*
     * This is for forgot password mail content
     */
    'forgot_password_mail' => [
        'name' => '{{name}}',
        'url' => '{{url}}',
        'contact_us' => '{{contact-us}}',

    ],

    'subscription_going_to_end_mail' => [
        'name' => '{{name}}',
        'number' => '{{number}}',
        'product' => '{{product}}',
        'expiry' => '{{expiry}}',
        'url' => '{{url}}',
    ],

    'subscription_over_mail' => [
        'name' => '{{name}}',
        'number' => '{{number}}',
        'product' => '{{product}}',
        'expiry' => '{{expiry}}',
        'url' => '{{url}}',
    ],

    'sales_manager_email' => [
        'name' => '{$name}',
        'manager_first_name' => '{{manager_first_name}}',
        'manager_last_name' => '{{manager_last_name}}',
        'manager_email' => '{{manager_email}}',
        'manager_code' => '{{manager_code}}',
        'manager_mobile' => '{{manager_mobile}}',
        'manager_skype' => '{{manager_skype}}',
    ],

    'account_manager_email' => [
        'name' => '{$name}',
        'manager_first_name' => '{$manager_first_name}}',
        'manager_last_name' => '{{manager_last_name}}',
        'manager_email' => '{{manager_email}}',
        'manager_code' => '{{manager_code}}',
        'manager_mobile' => '{{manager_mobile}}',
        'manager_skype' => '{{manager_skype}}',
    ],

    'autosubscription_going_to_end' => [
        'name' => '{{name}}',
        'number' => '{{number}}',
        'product' => '{{product}}',
        'expiry' => '{{expiry}}',
    ],

    'payment_failed' => [
        'name' => '{{name}}',
        'product' => '{{product}}',
        'total' => '{{total}}',
        'number' => '{{number}}',
        'expiry' => '{{expiry}}',
        'exception' => '{{exception}}',
        'url' => '{{url}}',
    ],
    'payment_successfull' => [
        'name' => '{{name}}',
        'product' => '{{product}}',
        'currency' => '{currency}}',
        'total' =>'{{total}}',
        'number' => '{{number}}',
    ],

    'card_failed' => [
        'name' => '{$name}',
        'product' => '{$product}',
        'total' => '{$total}',
        'number' => '{$number}',
        'expiry' => '{$expiry}',
        'exception' => '{$exception}',
        'url' => '{$url}',
    ],

    'Free_trail_gonna_expired' => [
        ' name' => '{$name}',
        'product' => '{$product}',
        'number' => '{$number}',
        'expiry' => '{$expiry}',
        'url' => '{$url}',
    ],
    'free_trail_expired' => [
        'name' => '{$name}',
        'product' => '{$product}',
        'number' => '{$number}',
        'expiry' => '{$expiry}',
        'url' => '{$url}',
    ],

    'cloud_deleted' => [
        'name' => '{{name}}',
        'product' => '{{product}}',
        'number' => '{{number}}',
        'expiry' => '{{expiry}}',
    ],

    'cloud_created' => [
        'name' => '{{name}}',
        'message' => '{{message}}',
    ],

];

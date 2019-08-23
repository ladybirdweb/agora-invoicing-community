<?php

return[
    /*
     * Cart page
     */
    'cart' => [
        'group'             => '{{group}}',
        'name'              => '{{name}}',
        'price'             => '{{price}}',
        'price-description' => '{{price-description}}',
        'feature'           => '<li>{{feature}}</li>',
        'subscription'      => '{{subscription}}',
        'url'               => '{{url}}',
    ],
    /*
     * This is for welcome mail content
     */
    'welcome_mail' => [
        'website_url'=> '{{website_url}}',
        'name'       => '{{name}}',
        'username'   => '{{username}}',
        'password'   => '{{password}}',
        'url'        => '{{url}}',
    ],

    /*
     * This is for order mail content
     */
    'order_mail' => [
        'name'          => '{{name}}',
        'downloadurl'   => '{{downloadurl}}',
        'invoiceurl'    => '{{invoiceurl}}',
        'product'       => '{{product}}',
        'number'        => '{{number}}',
        'expiry'        => '{{expiry}}',
        'url'           => '{{url}}',
        'serialkeyurl'  => '{{serialkeyurl}}',
        'knowledge_base'=> '{{knowledge_base}}',
    ],

    /*
     * This is for invoice mail content
     */
    'invoice_mail' => [
        'name'       => '{{name}}',
        'number'     => '{{number}}',
        'address'    => '{{address}}',
        'invoiceurl' => '{{invoiceurl}}',
        'total'      => '{{total}}',
        'content'    => '{{content}}',
        'currency'   => '{{currency}}',
    ],

    /*
     * This is for forgot password mail content
     */
    'forgot_password_mail' => [
        'name'      => '{{name}}',
        'url'       => '{{url}}',
        'contact-us'=> '{{contact-us}}',

    ],

    'subscription_going_to_end_mail' => [
        'name'    => '{{name}}',
        'number'  => '{{number}}',
        'product' => '{{product}}',
        'expiry'  => '{{expiry}}',
        'url'     => '{{url}}',
    ],

    'subscription_over_mail' => [
         'name'   => '{{name}}',
        'number'  => '{{number}}',
        'product' => '{{product}}',
        'expiry'  => '{{expiry}}',
        'url'     => '{{url}}',
    ],

    'sales_manager_email' => [
        'name'               => '{{name}}',
        'manager_first_name' => '{{manager_first_name}}',
        'manager_last_name'  => '{{manager_last_name}}',
        'manager_email'      => '{{manager_email}}',
        'manager_code'       => '{{manager_code}}',
        'manager_mobile'     => '{{manager_mobile}}',
        'manager_skype'      => '{{manager_skype}}',
    ],

    'account_manager_email' => [
        'name'               => '{{name}}',
        'manager_first_name' => '{{manager_first_name}}',
        'manager_last_name'  => '{{manager_last_name}}',
        'manager_email'      => '{{manager_email}}',
        'manager_code'       => '{{manager_code}}',
        'manager_mobile'     => '{{manager_mobile}}',
        'manager_skype'      => '{{manager_skype}}',
    ],
];

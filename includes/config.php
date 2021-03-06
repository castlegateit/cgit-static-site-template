<?php

/**
 * General site settings.
 *
 * @var [type]
 */
$config_site = [
    'title'        => 'Static Site Template',
    'heading'      => 'Static Site Template',
    'description'  => 'A template for static sites in PHP.',
    'public_email' => cgit_obfuscate('example@example.com'),
    'tel'          => '01234 567890',
    'facebook'     => 'http://www.facebook.com/example',
    'twitter'      => 'http://twitter.com/example',
];

$config_schema = [
    'name'         => 'Static Site Example Business',
    'email'        => 'example@example.com',
    'description'  => 'Example description of the organisation',
    'url'          => 'http://www.example.com',
    'address'      => 'Example Unit, Example Complex, Example City, AB1 2CD'
];

/**
 * SMTP email settings.
 */
$config_smtp = [

     // Fallback servers allowed e.g. 'smtp1.example.com;smtp2.example.com'.
    'host' => 'smtp.example.com',

     // Enable SMTP authentication.
    'auth' => true,

     // SMTP username.
    'user' => 'user@example.com',

     // SMTP password.
    'pass' => 'password',

     // SMTP server port.
    'port' => 465,

     // Values: (None: false, TLS: 'tls', SSL: 'ssl').
    'encryption' => 'ssl',

];

/**
 * Email sending settings.
 */
$config_mail = [

     // Email log file
    'log' => $_SERVER['DOCUMENT_ROOT'] . '/logs/contact.csv',

     // Enable or disable verbose error messages.
    'debug' => false,

     // Enable or disable HTML email.
    'html' => false,

     // Email subject.
    'subject' => 'Website Enquiry',

     // Email from address. Must match your mailbox address.
    'from' => [
        'name'    => 'Static Site Template',
        'address' => 'example@.org.uk'
    ],

     // Leave as false to prevent sending a reply-to header.
    'reply-to' => [
        'name'    => false,
        'address' => false
    ],

    // To
    'to' => [
        'example@example.com',
    ],

    // Cc
    'cc' => [
        // 'example@example.com',
    ],

    // Bcc
    'bcc' => [
        // 'example@example.com',
    ]
];

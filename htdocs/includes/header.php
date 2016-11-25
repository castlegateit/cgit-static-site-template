<?php

include $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

$title       = $pageinfo['title']       ?: $siteinfo['title'];
$description = $pageinfo['description'] ?: $siteinfo['description'];
$heading     = $pageinfo['heading']     ?: $siteinfo['heading'];

?><!DOCTYPE html>

<head>

<meta charset="utf-8" />

<title><?php echo $title; ?></title>

<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="description" content="<?php echo $description; ?>" />

<link rel="stylesheet" href="/static/css/style.css" />

</head>

<body>

<div class="header" role="banner">

    <h1><a href="/"><?php echo $heading; ?></a></h1>

    <div class="nav" role="navigation">
        <ul><?php

            echo cgit_nav_link('/', 'Home');
            echo cgit_nav_link('/about/', 'About');
            echo cgit_nav_link('/contact/', 'Contact');

        ?></ul>
    </div>

</div>

<div class="content">

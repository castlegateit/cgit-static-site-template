<?php

$pageinfo = array(
    'title'       => 'Static Site Template',
    'heading'     => 'Static Site Template',
    'description' => 'A template for static sites in PHP.',
);

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';

?>

    <div class="main" role="main">

        <h1>Home</h1>

        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

    </div>

<?php

include $_SERVER['DOCUMENT_ROOT'] . '/includes/side.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php';

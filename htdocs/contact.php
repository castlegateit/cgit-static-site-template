<?php

$pageinfo = array(
    'title'       => 'Contact | Static Site Template',
    'heading'     => 'Contact | Static Site Template',
    'description' => 'A template for static sites in PHP.',
);

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/contact.php';

?>

    <div class="main" role="main">

        <h1>Contact</h1>

        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

<?php if ($done): ?>
        <p class="message success">Your message has been sent. Thank you.</p>
<?php else: ?>

<?php if (array_key_exists('spam', $error)): ?>
        <p class="message error">Your message appears to be spam. Please remove any links and try again.</p>
<?php elseif (count($error)): ?>
        <p class="message error">Some fields contain errors. Please correct them and try again.</p>
<?php endif; ?>

        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">

            <div class="field">
                <label for="name" class="text-label">Name</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name) ?>" class="text-input" required />
                <?php echo cgit_error_message('name'); ?>
            </div>

            <div class="field">
                <label for="email" class="text-label">Email</label>
                <input type="email" name="email" id="email" class="text-input" value="<?php echo htmlspecialchars($email); ?>" required />
                <?php echo cgit_error_message('email'); ?>
            </div>

            <div class="field">
                <label for="subject" class="text-label">Subject</label>
                <input type="text" name="subject" id="subject" class="text-input" value="<?php echo htmlspecialchars($subject); ?>" />
            </div>

            <div class="field">
                <label for="message" class="text-label">Message</label>
                <textarea name="message" id="message" class="text-input" required><?php echo htmlspecialchars($message); ?></textarea>
                <?php echo cgit_error_message('message'); ?>
            </div>

            <div class="field submit">
                <button class="button">Send Message</button>
            </div>

        </form>

<?php endif; ?>

    </div>

<?php

include $_SERVER['DOCUMENT_ROOT'] . '/includes/side.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php';

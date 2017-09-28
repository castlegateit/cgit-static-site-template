<?php

$mail = new PHPMailer;

/**
 * Configure email debugging.
 */
if ($config_mail['debug']) {
    $mail->SMTPDebug = 3;
}

/**
 * Enable SMTP.
 */
$mail->isSMTP();

/**
 * SMTP host.
 */
$mail->Host = $config_smtp['host'];

/**
 * SMTP username.
 */
$mail->Username = $config_smtp['user'];

/**
 * SMTP password.
 */
$mail->Password = $config_smtp['pass'];

/**
 * SMTP port.
 */
$mail->Port = $config_smtp['port'];

/**
 * SMTP authentication
 */
$mail->SMTPAuth = $config_smtp['auth'];

/**
 * SMTP encryption.
 */
if ($config_smtp['encryption'] !== false) {
    $mail->SMTPSecure = $config_smtp['encryption'];
}

/**
 * Subject.
 */
$mail->Subject = $config_mail['subject'];

/**
 * From address.
 */
$mail->setFrom($config_mail['from']['address'], $config_mail['from']['name']);

/**
 * Reply to.
 */
if ($config_mail['reply-to']['address'] !== false) {
    if ($config_mail['reply-to']['name']) {
        $mail->addReplyTo($config_mail['reply-to']['address'], $config_mail['reply-to']['name']);
    } else {
        $mail->addReplyTo($config_mail['reply-to']['address']);
    }
}

/**
 * To.
 */
if (!empty($config_mail['to'])) {
    foreach ($config_mail['to'] as $to) {
        $mail->addAddress($to);
    }
}

/**
 * CC.
 */
if (!empty($config_mail['cc'])) {
    foreach ($config_mail['cc'] as $cc) {
        $mail->addCC($cc);
    }
}

/**
 * BCC.
 */
if (!empty($config_mail['bcc'])) {
    foreach ($config_mail['bcc'] as $bcc) {
        $mail->addBCC($bcc);
    }
}

/**
 * Configure HTML email.
 */
if ($config_mail['html']) {
    $mail->isHTML(true);
}


/**
 * Blocked IPs
 */
$blocked_ips = [
    // '31.184.238.52',
];

/**
 * Contact form fields
 */
$fields = [
    'name',
    'email',
    'subject',
    'message',
];

/**
 * Array to hold errors
 */
$error = [];

/**
 * Check whether form is completed and sent
 */
$done = false;


/*
 * Create Email Log files
 */
if ($config_mail['log']) {
    define('EMAIL_LOG', $config_mail['log']);
}

/**
 * Function to print input class name on validation
 */
function cgit_input_valid($field_name) {
    global $error;

    if (!empty($_POST)) {
        if (array_key_exists($field_name, $error)) {
            return 'invalid';
        }

        return 'valid';
    }
}

/**
 * Function to print error message
 */
function cgit_error_message($field_name) {
    global $error;

    if (array_key_exists($field_name, $error)) {
        return "<span class='error'>{$error[$field_name]}</span>";
    }
}

/**
 * Clean POST data and assign to named variables
 */
foreach ($fields as $key => $value) {
    $data = isset($_POST[$value]) ? $_POST[$value] : '';
    $data = trim($data);
    $data = stripslashes($data); // prevent escaped quotes and slashes
    $$value = $data;
}

/**
 * Validate submitted data and send if no errors
 */
if (!empty($_POST)) {
    /**
     * Check required fields
     */

    // Check name
    if (empty($name)) {
        $error['name'] = 'This is a required field';
    }

    // Check email
    if (empty($email)) {
        $error['email'] = 'This is a required field';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = 'Please enter a valid email address';
    } else {
        // Verify domain is valid
        list($addr,$domain) = explode('@', $email);
        $domain .= '.';

        if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
            $error['email'] = 'Please enter a valid email address';
        }
    }

    // Check message
    if (empty($message)) {
        $error['message'] = 'This is a required field';
    }

    /**
     * Check for spam
     */
    $filter = 'bcc:|cc:|%0ato:|\nto:|url:|url=|multipart|content-type|<a|' .
        '&lt;a|<script|&lt;script|http:|https:|ftp:|www.|document.cookie|' .
        'document.write';
    $forbidden = preg_match("/$filter/i", implode('', $_POST));
    $blocked = in_array($_SERVER['REMOTE_ADDR'], $blocked_ips);

    if ($forbidden || $blocked) {
        $error['spam'] = 'spam';
    }

    /**
     * If no errors, send message
     *
     * If no errors are detected, the message is assembled using the form
     * input and the settings defined at the start of the file. If the email
     * is to be sent in HTML format, define the email headers here.
     *
     * If EMAIL_LOG has been defined and a native CSV function exists, the
     * output is also written to a log file.
     */
    if (count($error) == 0) {
        // Sender IP
        $sender = $_SERVER['REMOTE_ADDR'];

        // Assemble message body
        $mail->Body = "Name: $name\n\n" .
            "Email: $email\n\n" .
            "Subject: $subject\n\n" .
            "Message:\n\n$message\n\n" .
            "Sender IP: $sender";

        // Alternative body in plain text for non HTML mail clients when HTML is used.
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        // Send message
        $email_result = $mail->send();

        // Did the email send?
        if ($email_result) {
            // Write to log file
            if (defined('EMAIL_LOG') && function_exists('fputcsv')) {
                $log = fopen(EMAIL_LOG, 'a');
                $row = array(date('Y-m-d H:i'), $name, $email, $message, $sender);

                fputcsv($log, $row);
            }

            // Completed
            $done = true;
        } else {
            // Email was not sent due to an error.
            $done = false;
            $error['send'] = 'error';
        }
    }
}

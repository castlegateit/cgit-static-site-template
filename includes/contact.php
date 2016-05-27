<?php

/**
 * Settings and definitions
 */
define('EMAIL_TO', $siteinfo['form_email']);
define('EMAIL_CC', '');
define('EMAIL_BCC', '');
define('EMAIL_FROM', 'example@example.com');
define('EMAIL_SUBJECT', 'Website Enquiry');
define('EMAIL_LOG', $_SERVER['DOCUMENT_ROOT'] . '/logs/contact.csv');

/**
 * Blocked IPs
 */
$blocked_ips = array(
    '31.184.238.52',
);

/**
 * Contact form fields
 */
$fields = array(
    'name',
    'email',
    'subject',
    'message',
);

/**
 * Array to hold errors
 */
$error = array();

/**
 * Check whether form is completed and sent
 */
$done = false;

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
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
        $email_body = "Name: $name\n\n" .
            "Email: $email\n\n" .
            "Subject: $subject\n\n" .
            "Message:\n\n$message\n\n" .
            "Sender IP: $sender";

        // Assemble message headers
        $email_headers  = "From: $name <$email>"; // alternatively EMAIL_FROM
        $email_headers .= EMAIL_CC != '' ? "\nCc:" . EMAIL_CC : '';
        $email_headers .= EMAIL_BCC != '' ? "\nBcc:" . EMAIL_BCC : '';

        // Send HTML email
        // $email_headers .= "\nMIME-Version: 1.0";
        // $email_headers .= "\nContent-Type: text/html; charset=UTF-8";

        // Send message
        $email_result = mail(EMAIL_TO, EMAIL_SUBJECT, $email_body, $email_headers);

        // Write to log file
        if (defined('EMAIL_LOG') && function_exists('fputcsv')) {

            $log = fopen(EMAIL_LOG, 'a');
            $row = array(date('Y-m-d H:i'), $name, $tel, $email, $message, $sender);

            fputcsv($log, $row);

        }

        // Completed
        $done = true;

    }

}

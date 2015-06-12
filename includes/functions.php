<?php

/**
 * Return an obfuscated string
 *
 * Replaces characters within a string with decimal or hexadecimal HTML entities
 * at random. Excludes certain forbidden characters.
 */
function cgit_obfuscate($str) {

    $output = '';
    $forbidden = array('@', '.', ':');

    for ($i = 0; $i < strlen($str); $i++) {
        $obfuscate = in_array($str[$i], $forbidden) ? 1 : rand(0, 1);
        if ($obfuscate) {
            $code = ord($str[$i]);
            $hex = rand(0, 1);
            if ($hex) {
                $code = dechex($code);
                while (strlen($code) < 4) {
                    $code = "0$code";
                }
                $code = "x$code";
            }
            $output .= "&#$code;";
        } else {
            $output .= $str[$i];
        }
    }

    return $output;

}

/**
 * Return an obfuscated email link
 *
 * Generates an obfuscated HTML mailto: link, with optional link text. If no
 * text is entered, the email address is used for the text of the link.
 */
function cgit_obfuscate_link($str, $text = false) {

    $protocol = cgit_obfuscate('mailto:');
    $address = cgit_obfuscate($str);
    $text = $text ? $text : $address;

    return "<a href='$protocol$address'>$text</a>";

}

/**
 * Return navigation link
 *
 * Generates a navigation menu link, including list item element. The end tag
 * can be omitted by setting $close to false to allow nesting of second level
 * links. Use $level to create second level navigation links.
 */
function cgit_nav_link($path, $text, $level = 0, $close = true) {

    $current = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    $segment = explode('/', trim($path, '/'));
    $endtag = $close ? '</li>' : '';
    $attributes = '';

    if (isset($current[$level]) && $current[$level] == $segment[$level]) {
        $attributes = ' class="active"';
    }

    return "<li$attributes><a href='$path'>$text</a>$endtag\n";

}

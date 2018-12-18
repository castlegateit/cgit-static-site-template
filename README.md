# Static Site Template #

A basic static site template written in PHP.

## Requirements ##

*   Apache 2.2 or above
*   PHP 7.0 or above.
*   Composer

## Features ##

*   Basic CSS, based on [Normalize.css](http://necolas.github.io/normalize.css/) and [Terminus](https://github.com/castlegateit/terminus).
*   Page title, description, and heading with global default values.
*   Navigation links with automatic active state.
*   Contact form with log.
*   Error pages.
*   Functions for email obfuscation.

## Usage ##

### Directories ###

*   `/static/css`: CSS files.
*   `/static/js`: JavaScript files.
*   `/static/fonts`: web font files.
*   `/static/images`: design-related image files.
*   `/content`: content-related media, including images and documents.

### Second level pages ###

You can add second level pages by placing them in a directory. The "parent" page is the `index.php` file in that directory.

### SEO ###

The default title, heading, and description are entered in the `$siteinfo` array in the main `config.php` file. These can be overridden on individual pages by editing the `$pageinfo` array at the top of each page. See the default pages for examples.

### Schemas ### 

The default header will inject a basic Organization schema. You need to either add the appropriate data yourself or remove the include.
To add data to the schema, simply edit the `/includes/config.php` file. You can specify any valid Organization property in the `$config_schema` array and the included `organization.php` file will iterate
over the array, build the schema script and output it to the page head. 

### Logs ###

By default, the contact form will log submissions to `/logs/contact.csv` in the document root directory. You may need to set the permissions on this directory to `777` to make this work. Note that this directory is hidden using `.htaccess`.

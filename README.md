URL Shortener Bundle for Symfony2
=================================

A basic implementation of URL Shortener Bundle for Symfony2

## Installation

### Step 1: Download the Bundle

Add the bundle in your composer.json :

```bash
"require": {
    "jaxyeh/url-shortener-bundle": "dev-master"
}

```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding the following line in the `app/AppKernel.php`
file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Jaxyeh\UrlShortenerBundle\JaxyehUrlShortenerBundle(),
        );

        // ...
    }

    // ...
}
```

### Step 3: Set Configuration

Add the following paramters to your configuration file:

```bash
parameters:
    jaxyeh_url.hashids.salt: mysalt
    jaxyeh_url.hashids.min_length: 5
```

### Step 4: Generate Database Schema

Finally, you have to generate your database schema with this Symfony2 command:

```bash
php app/console doctrine:schema:update --force
```

## License

This script is available under the MIT license.

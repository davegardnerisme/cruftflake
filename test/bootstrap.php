<?php

// include the pure-php class loader, if not already exists (eg: via binary)
if (!class_exists('\SplClassLoader')) {
    include dirname(__FILE__)
            . '/../dependencies/spl-class-loader/SplClassLoader.php';
}
// autoload
$classLoader = new \SplClassLoader(
        'Davegardnerisme\CruftFlake',
        dirname(__FILE__) . '/../src/'
        );
$classLoader->register();

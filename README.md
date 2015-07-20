===========================================
# PHP Hooks for Dredd API Testing Framework
===========================================
[![Build Status](https://travis-ci.org/ddelnano/dredd-hooks-php.svg?branch=master)](https://travis-ci.org/ddelnano/dredd-hooks-php)

##About
======
This package contains a PHP Dredd hook handler which provides a bridge between the Dredd API Testing Framework and the PHP environment to allow for implementation of 
testing hooks provided by Dredd in PHP.

Example

```php
<?php

use Dredd\Hooks;

Hooks::beforeAll(function(&$transaction) {

    // set up database for tests
});
```



# Todo
- Sample Laravel application
- Better 


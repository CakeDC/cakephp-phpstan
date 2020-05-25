# CakePHP 4 extension for PHPStan


[![Build Status](https://secure.travis-ci.org/CakeDC/cakephp-phpstan.png?branch=master)](http://travis-ci.org/CakeDC/cakephp-phpstan)
[![Coverage Status](https://img.shields.io/codecov/c/gh/CakeDC/cakephp-phpstan.svg?style=flat-square)](https://codecov.io/gh/CakeDC/cakephp-phpstan)
[![Downloads](https://poser.pugx.org/CakeDC/cakephp-phpstan/d/total.png)](https://packagist.org/packages/CakeDC/cakephp-phpstan)
[![Latest Version](https://poser.pugx.org/CakeDC/cakephp-phpstan/v/stable.png)](https://packagist.org/packages/CakeDC/cakephp-phpstan)
[![License](https://poser.pugx.org/CakeDC/cakephp-phpstan/license.svg)](https://packagist.org/packages/CakeDC/cakephp-phpstan)

* [PHPStan](https://phpstan.org/)
* [CakePHP](https://cakephp.org/)

This extension provides following features:

1. Provide correct return type `\Cake\ORM\Locator\LocatorInterface::get`
1. Provide correct return type `Cake\Controller\Controller::loadModel`
1. Provide correct return type `Cake\Controller\Controller::loadComponent`
1. Provide correct return type `Cake\Command\Command::loadModel`
1. Provide correct return type `Cake\Console\Shell::loadModel`
1. Provide correct return type `Cake\Mailer\Mailer::loadModel`
1. Provide correct return type `Cake\View\Cell::loadModel`
1. Provide correct return type `Cake\Console\Shell::helper`

## Installation

To use this extension, require it in [Composer](https://getcomposer.org/):

```
composer require --dev cakedc/cakephp-phpstan
```


If you also install [phpstan/extension-installer](https://github.com/phpstan/extension-installer) then you're all set!

<details>
    <summary>Manual installation</summary>

If you don't want to use `phpstan/extension-installer`, include extension.neon in your project's PHPStan config:
```
includes:
    - vendor/cakedc/cakephp-phpstan/extension.neon
```

</details>

### Tips
To make your life easier make sure to have @mixin and @method annotations in your table classes.
The @mixin annotation will help phpstan know you are using methods from behavior, and @method annotations
will allow it to know the correct return types for methods like Table::get, Table::newEntity.

You can easily update annotations with the plugin [IdeHelper](https://github.com/dereuromark/cakephp-ide-helper)

Support
-------

For bugs and feature requests, please use the [issues](https://github.com/CakeDC/cakephp-phpstan/issues) section of this repository.

Commercial support is also available, [contact us](https://www.cakedc.com/contact) for more information.

Contributing
------------

If you'd like to contribute new features, enhancements or bug fixes to the plugin, please read our [Contribution Guidelines](https://www.cakedc.com/contribution-guidelines) for detailed instructions.

License
-------

Copyright 2020 Cake Development Corporation (CakeDC). All rights reserved.

Licensed under the [MIT](http://www.opensource.org/licenses/mit-license.php) License. Redistributions of the source code included in this repository must retain the copyright notice found in each file

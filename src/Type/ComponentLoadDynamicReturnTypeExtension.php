<?php
declare(strict_types=1);

/**
 * Copyright 2020, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2020, Cake Development Corporation (https://www.cakedc.com)
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\PHPStan\Type;

use Cake\Controller\Component;
use Cake\Controller\Controller;
use CakeDC\PHPStan\Traits\BaseCakeRegistryReturnTrait;
use PHPStan\Type\DynamicMethodReturnTypeExtension;

class ComponentLoadDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    use BaseCakeRegistryReturnTrait;

    /**
     * @var string
     */
    private string $className;
    /**
     * @var string
     */
    private string $methodName;

    /**
     * @var string
     */
    protected string $defaultClass;
    /**
     * @var string
     */
    protected string $namespaceFormat;

    /**
     * TableLocatorDynamicReturnTypeExtension constructor.
     */
    public function __construct()
    {
        $this->className = Controller::class;
        $this->methodName = 'loadComponent';
        $this->defaultClass = Component::class;
        $this->namespaceFormat = '%s\\Controller\Component\\%sComponent';
    }
}

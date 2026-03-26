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

use Cake\Console\ConsoleIo;
use Cake\Console\Helper;
use CakeDC\PHPStan\Traits\BaseCakeRegistryReturnTrait;
use CakeDC\PHPStan\Utility\CakeNameRegistry;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;

class ConsoleHelperLoadDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    use BaseCakeRegistryReturnTrait {
        getCakeType as _getCakeType;
    }

    /**
     * @var class-string
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

    private readonly CakeNameRegistry $cakeNameRegistry;

    /**
     * TableLocatorDynamicReturnTypeExtension constructor.
     */
    public function __construct(?CakeNameRegistry $cakeNameRegistry = null)
    {
        $this->className = ConsoleIo::class;
        $this->methodName = 'helper';
        $this->defaultClass = Helper::class;
        $this->namespaceFormat = '%s\\Command\Helper\\%sHelper';
        $this->cakeNameRegistry = $cakeNameRegistry ?? CakeNameRegistry::instance();
    }

    /**
     * Before calling BaseCakeRegistryReturnTrait::getCakeType uppercase the
     * first letter as done in the method ConsoleIo::helper
     *
     * @param string $baseName
     * @return \PHPStan\Type\ObjectType
     */
    protected function getCakeType(string $baseName): Type
    {
        $baseName = ucfirst($baseName);

        return $this->_getCakeType($baseName);
    }
}

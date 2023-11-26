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

use Cake\Datasource\EntityInterface;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
use CakeDC\PHPStan\Traits\BaseCakeRegistryReturnTrait;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ArrayType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\IntegerType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class TableEntityDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    use BaseCakeRegistryReturnTrait;

    /**
     * @var string
     */
    private string $className;
    /**
     * @var array
     */
    private array $methodNames = [
        'get',
        'newEntity',
        'newEntities',
        'newEmptyEntity',
        'patchEntity',
        'findOrCreate',
        'saveOrFail',
    ];

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
        $this->className = Table::class;
        $this->defaultClass = EntityInterface::class;
        $this->namespaceFormat = '%s\\Model\Entity\\%s';
    }

    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @return bool
     */
    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return in_array($methodReflection->getName(),$this->methodNames);
    }

    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     * @param \PHPStan\Analyser\Scope $scope
     * @return \PHPStan\Type\Type
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): Type {
        $className = $scope->getType($methodCall->var)->getReferencedClasses()[0] ?? null;
        if ($className === null || $className === Table::class) {
            return $this->getTypeWhenNotFound($methodReflection);
        }
        $entityClass = $this->getEntityClassByTableClass($className);

        if ($entityClass !== null && class_exists($entityClass)) {
            if ($methodReflection->getName() == 'newEntities') {
                return new ArrayType(new IntegerType(), new ObjectType($entityClass));
            }
            return new ObjectType($entityClass);
        }

        return $this->getTypeWhenNotFound($methodReflection);
    }

    /**
     * @param string $className
     * @return string|null
     */
    protected function getEntityClassByTableClass(string $className): ?string
    {
        $parts = explode('\\', $className);
        $count = count($parts);
        $nameIndex = $count - 1;
        $folderIndex = $count - 2;
        if ($count < 3 || $parts[$folderIndex] !== 'Table') {
            return null;
        }
        $name = str_replace('Table', '', $parts[$nameIndex]);
        $name = Inflector::singularize($name);
        $parts[$folderIndex] = 'Entity';
        $parts[$nameIndex] = $name;

        return implode('\\', $parts);
    }
}

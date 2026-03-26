<?php
declare(strict_types=1);

/**
 * Copyright 2023, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2020, Cake Development Corporation (https://www.cakedc.com)
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\PHPStan\Type;

use Cake\Datasource\EntityInterface;
use CakeDC\PHPStan\Traits\BaseCakeRegistryReturnTrait;
use CakeDC\PHPStan\Utility\CakeNameRegistry;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\IntegerType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

class RepositoryFirstArgIsTheReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    use BaseCakeRegistryReturnTrait;

    /**
     * @var array<string>
     */
    private array $methodNames = [
        'patchEntity',
        'patchEntities',
        'save',
        'saveOrFail',
        'saveMany',
        'saveManyOrFail',
        'deleteMany',
        'deleteManyOrFail',
    ];
    /**
     * @var class-string
     */
    protected string $className;

    /**
     * @var string
     */
    protected string $defaultClass;
    /**
     * @var string
     */
    protected string $namespaceFormat;

    /**
     * @param class-string $className  The target className.
     */
    public function __construct(string $className, private readonly CakeNameRegistry $cakeNameRegistry)
    {
        $this->className = $className;
        $this->defaultClass = EntityInterface::class;
        $this->namespaceFormat = '%s\\Model\Entity\\%s';
    }

    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @return bool
     */
    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return in_array($methodReflection->getName(), $this->methodNames, true);
    }

    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     * @param \PHPStan\Analyser\Scope $scope
     * @return \PHPStan\Type\Type|null
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope,
    ): ?Type {
        $args = $methodCall->getArgs();
        if (count($args) === 0) {
            return null;
        }

        $type = $scope->getType($args[0]->value);

        $name = $methodReflection->getName();
        if (in_array($name, ['save', 'saveMany', 'deleteMany'], true)) {
            if ($type instanceof UnionType) {
                $types = $type->getTypes();
                $types[] = new ConstantBooleanType(false);
            } else {
                $types = [$type, new ConstantBooleanType(false)];
            }

            return new UnionType($types);
        }
        if ($methodReflection->getName() === 'patchEntities') {
            if (!$type->isIterable()->yes()) {
                return null;
            }
            $valueType = $type->getIterableValueType();
            if ($valueType->isObject()->yes()) {
                return new ArrayType(new IntegerType(), $valueType);
            }

            return null;
        }

        return $type;
    }
}

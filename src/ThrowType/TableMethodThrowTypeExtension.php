<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\ThrowType;

use Cake\ORM\Association;
use Cake\ORM\Table;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MissingMethodFromReflectionException;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\DynamicMethodThrowTypeExtension;
use PHPStan\Type\Type;

class TableMethodThrowTypeExtension implements DynamicMethodThrowTypeExtension
{
    /**
     * @var \PHPStan\Reflection\ReflectionProvider
     */
    protected ReflectionProvider $reflectionProvider;

    /**
     * @param \PHPStan\Reflection\ReflectionProvider $reflectionProvider
     */
    public function __construct(ReflectionProvider $reflectionProvider)
    {
        $this->reflectionProvider = $reflectionProvider;
    }

    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @return bool
     */
    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'get';
    }

    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     * @param \PHPStan\Analyser\Scope $scope
     * @return \PHPStan\Type\Type|null
     */
    public function getThrowTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope,
    ): ?Type {
        $methodName = $methodReflection->getName();
        $type = $scope->getType($methodCall->var);
        $classReflection = $type->getObjectClassReflections()[0];
        $isAssociation = $classReflection->is(Association::class);
        if ($isAssociation) {
            return $this->getThrowType($methodName, $scope);
        }

        if (!$classReflection->is(Table::class)) {
            return null;
        }

        $tag = $classReflection->getResolvedPhpDoc()?->getMethodTags()['get'] ?? null;
        if ($tag === null) {
            return null;
        }

        return $this->getThrowType($methodName, $scope);
    }

    /**
     * @param string $methodName
     * @param \PHPStan\Analyser\Scope $scope
     * @return \PHPStan\Type\Type|null
     */
    protected function getThrowType(string $methodName, Scope $scope): ?Type
    {
        $reflection = $this->reflectionProvider->getClass(Table::class);

        try {
            return $reflection->getMethod($methodName, $scope)->getThrowType();
        } catch (MissingMethodFromReflectionException $e) {
            return null;
        }
    }
}

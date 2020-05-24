<?php

/**
 * @source https://github.com/cakephp/cakephp
 */

namespace CakeDC\PHPStan\Method;

use PHPStan\Reflection\ClassMemberReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\ObjectType;

class TableFindByPropertyMethodReflection implements MethodReflection
{
    /** @var string */
    private $name;

    /** @var \PHPStan\Reflection\ClassReflection */
    private $declaringClass;

    public function __construct(string $name, ClassReflection $declaringClass)
    {
        $this->name = $name;
        $this->declaringClass = $declaringClass;
    }

    public function getDeclaringClass(): ClassReflection
    {
        return $this->declaringClass;
    }

    public function getPrototype(): ClassMemberReflection
    {
        return $this;
    }

    public function isStatic(): bool
    {
        return false;
    }

    /**
     * @return \PHPStan\Reflection\ParameterReflection[]
     */
    public function getParameters(): array
    {
        return [];
    }

    public function isVariadic(): bool
    {
        return false;
    }

    public function isPrivate(): bool
    {
        return false;
    }

    public function isPublic(): bool
    {
        return true;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getReturnType(): \PHPStan\Type\ObjectType
    {
        return new ObjectType('\Cake\ORM\Query');
    }

    public function getDocComment(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getVariants(): array
    {
        return [new \PHPStan\Reflection\TrivialParametersAcceptor()];
    }

    public function isDeprecated(): \PHPStan\TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function getDeprecatedDescription(): ?string
    {
        return null;
    }

    public function isFinal(): \PHPStan\TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function isInternal(): \PHPStan\TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function getThrowType(): ?\PHPStan\Type\Type
    {
        return null;
    }

    public function hasSideEffects(): \PHPStan\TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }
}

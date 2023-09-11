<?php
declare(strict_types=1);

/**
 * @source https://github.com/cakephp/cakephp
 */

namespace CakeDC\PHPStan\Method;

use PHPStan\Reflection\ClassMemberReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\TrivialParametersAcceptor;
use PHPStan\TrinaryLogic;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class TableFindByPropertyMethodReflection implements MethodReflection
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var \PHPStan\Reflection\ClassReflection
     */
    private ClassReflection $declaringClass;

    /**
     * @param string $name
     * @param \PHPStan\Reflection\ClassReflection $declaringClass
     */
    public function __construct(string $name, ClassReflection $declaringClass)
    {
        $this->name = $name;
        $this->declaringClass = $declaringClass;
    }

    /**
     * @return \PHPStan\Reflection\ClassReflection
     */
    public function getDeclaringClass(): ClassReflection
    {
        return $this->declaringClass;
    }

    /**
     * @return \PHPStan\Reflection\ClassMemberReflection
     */
    public function getPrototype(): ClassMemberReflection
    {
        return $this;
    }

    /**
     * @return bool
     */
    public function isStatic(): bool
    {
        return false;
    }

    /**
     * @return array<\PHPStan\Reflection\ParameterReflection>
     */
    public function getParameters(): array
    {
        return [];
    }

    /**
     * @return bool
     */
    public function isVariadic(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isPrivate(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return \PHPStan\Type\ObjectType
     */
    public function getReturnType(): ObjectType
    {
        return new ObjectType('\Cake\ORM\Query');
    }

    /**
     * @return string|null
     */
    public function getDocComment(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getVariants(): array
    {
        return [new TrivialParametersAcceptor()];
    }

    /**
     * @return \PHPStan\TrinaryLogic
     */
    public function isDeprecated(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    /**
     * @return string|null
     */
    public function getDeprecatedDescription(): ?string
    {
        return null;
    }

    /**
     * @return \PHPStan\TrinaryLogic
     */
    public function isFinal(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    /**
     * @return \PHPStan\TrinaryLogic
     */
    public function isInternal(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    /**
     * @return \PHPStan\Type\Type|null
     */
    public function getThrowType(): ?Type
    {
        return null;
    }

    /**
     * @return \PHPStan\TrinaryLogic
     */
    public function hasSideEffects(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }
}

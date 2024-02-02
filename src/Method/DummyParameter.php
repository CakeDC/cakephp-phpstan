<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Method;

use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\PassedByReference;
use PHPStan\Type\Type;

class DummyParameter implements ParameterReflection
{
    /**
     * @var string
     */
    private string $name;
    /**
     * @var \PHPStan\Type\Type
     */
    private Type $type;
    /**
     * @var bool
     */
    private bool $optional;
    /**
     * @var bool
     */
    private bool $variadic;
    /**
     * @var ?\PHPStan\Type\Type
     */
    private ?Type $defaultValue = null;
    /**
     * @var \PHPStan\Reflection\PassedByReference
     */
    private PassedByReference $passedByReference;

    /**
     * @param string $name
     * @param \PHPStan\Type\Type $type
     * @param bool $optional
     * @param \PHPStan\Reflection\PassedByReference|null $passedByReference
     * @param bool $variadic
     * @param \PHPStan\Type\Type|null $defaultValue
     */
    public function __construct(
        string $name,
        Type $type,
        bool $optional,
        ?PassedByReference $passedByReference,
        bool $variadic,
        ?Type $defaultValue
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->optional = $optional;
        $this->variadic = $variadic;
        $this->defaultValue = $defaultValue;
        $this->passedByReference = $passedByReference ?? PassedByReference::createNo();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isOptional(): bool
    {
        return $this->optional;
    }

    /**
     * @return \PHPStan\Type\Type
     */
    public function getType(): Type
    {
        return $this->type;
    }

    /**
     * @return \PHPStan\Reflection\PassedByReference
     */
    public function passedByReference(): PassedByReference
    {
        return $this->passedByReference;
    }

    /**
     * @return bool
     */
    public function isVariadic(): bool
    {
        return $this->variadic;
    }

    /**
     * @return \PHPStan\Type\Type|null
     */
    public function getDefaultValue(): ?Type
    {
        return $this->defaultValue;
    }
}

<?php
declare(strict_types=1);

/**
 * @source https://github.com/cakephp/cakephp
 */

namespace CakeDC\PHPStan\Method;

use Cake\ORM\Query\SelectQuery;
use Cake\Utility\Inflector;
use PHPStan\Reflection\ClassMemberReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Reflection\MethodReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\MixedType;
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
     * @var array<\PHPStan\Reflection\FunctionVariant>
     */
    private array $variants;

    /**
     * @param string $name
     * @param \PHPStan\Reflection\ClassReflection $declaringClass
     */
    public function __construct(string $name, ClassReflection $declaringClass)
    {
        $this->name = $name;

        $this->declaringClass = $declaringClass;
        $params = array_map(fn ($field) => new DummyParameter(
            $field,
            new MixedType(),
            false,
            null,
            false,
            null
        ), $this->getParams($name));

        $returnType = new ObjectType(SelectQuery::class);

        $this->variants = [
            new FunctionVariant(
                TemplateTypeMap::createEmpty(),
                null,
                $params,
                false,
                $returnType
            ),
        ];
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
        return new ObjectType('\Cake\ORM\Query\SelectQuery');
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
        return $this->variants;
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

    /**
     * @param string $method
     * @return array<string>
     */
    protected function getParams(string $method): array
    {
        $method = Inflector::underscore($method);
        $fields = substr($method, 8);
        if (str_contains($fields, '_and_')) {
            return explode('_and_', $fields);
        }

        if (str_contains($fields, '_or_')) {
            return explode('_or_', $fields);
        }

        return [$fields];
    }
}

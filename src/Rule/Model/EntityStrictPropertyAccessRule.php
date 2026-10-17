<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Rule\Model;

use Cake\ORM\Entity;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Node\Printer\ExprPrinter;
use PHPStan\Reflection\MissingPropertyFromReflectionException;
use PHPStan\Reflection\Php\PhpMethodFromParserNodeReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

class EntityStrictPropertyAccessRule implements Rule
{
    /**
     * @var string
     */
    protected string $identifier = 'cake.entity.strictPropertyAccess';

    /**
     * @param \PHPStan\Node\Printer\ExprPrinter $printer
     */
    public function __construct(
        private ExprPrinter $printer,
        private ReflectionProvider $reflectionProvider,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getNodeType(): string
    {
        return Assign::class;
    }

    /**
     * @param \PhpParser\Node $node
     * @param \PHPStan\Analyser\Scope $scope
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        assert($node instanceof Assign);

        if (!$node->var instanceof PropertyFetch) {
            return [];
        }
        if (
            !$node->var->var instanceof Variable
            || $node->var->var->name !== 'this'
        ) {
            return [];
        }
        $classReflection = $scope->getClassReflection();
        if ($classReflection === null) {
            return [];
        }
        $entityBaseReflection = $this->reflectionProvider->getClass(Entity::class);
        if (
            !$classReflection->isSubclassOfClass($entityBaseReflection)
            && $classReflection->getName() !== Entity::class
        ) {
            return [];
        }
        if (!$node->var->name instanceof Identifier) {
            return [];
        }
        $propertyName = $node->var->name->toString();
        //Ignore properties from \Cake\ORM\Entity
        if ($entityBaseReflection->hasNativeProperty($propertyName)) {
            return [];
        }
        if (!$classReflection->hasNativeProperty($propertyName)) {
            return [];
        }
        $currentFunction = $scope->getFunction();
        if (
            $currentFunction instanceof PhpMethodFromParserNodeReflection
            && $currentFunction->getHookedPropertyName() === $propertyName
        ) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Modifying property "%s" in entity class "%s" is not allowed. Use $this->set(\'%s\', %s) for proper dirty tracking.',// phpcs:ignore
                $propertyName,
                $classReflection->getName(),
                $propertyName,
                $this->printer->printExpr($node->expr),
            ))
                ->identifier($this->identifier)
                ->build(),
        ];
    }
}

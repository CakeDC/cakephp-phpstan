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

use Cake\Mailer\MailerAwareTrait;
use CakeDC\PHPStan\Utility\CakeNameRegistry;
use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ExpressionTypeResolverExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ThisType;
use PHPStan\Type\Type;

class GetMailerExpressionTypeResolverExtension implements ExpressionTypeResolverExtension
{
    /**
     * @var string
     */
    protected string $methodName;
    protected string $namespaceFormat;
    protected string $targetTrait;

    /**
     * Contructor
     */
    public function __construct()
    {
        $this->targetTrait = MailerAwareTrait::class;
        $this->methodName = 'getMailer';
        $this->namespaceFormat = '%s\\Mailer\\%sMailer';
    }

    /**
     * @param \PhpParser\Node\Expr $expr
     * @param \PHPStan\Analyser\Scope $scope
     * @return \PHPStan\Type\Type|null
     */
    public function getType(Expr $expr, Scope $scope): ?Type
    {
        if (
            !$expr instanceof Expr\MethodCall
            || !$expr->name instanceof Identifier
            || $expr->name->toString() !== $this->methodName
        ) {
            return null;
        }

        $callerType = $scope->getType($expr->var);

        if (
            !$callerType instanceof ThisType
            || !$this->isFromTargetTrait($callerType->getClassReflection())
        ) {
            return null;
        }

        $value = $expr->getArgs()[0]->value ?? null;
        if (!$value instanceof String_) {
            return null;
        }
        $className = CakeNameRegistry::getClassName($value->value, $this->namespaceFormat);
        if ($className !== null) {
            return new ObjectType($className);
        }

        return null;
    }

    /**
     * @param \PHPStan\Reflection\ClassReflection $reflection
     * @return bool
     */
    protected function isFromTargetTrait(ClassReflection $reflection): bool
    {
        foreach ($reflection->getTraits() as $trait) {
            if ($trait->getName() === $this->targetTrait) {
                return true;
            }
        }
        foreach ($reflection->getParents() as $parent) {
            if ($this->isFromTargetTrait($parent)) {
                return true;
            }
        }

        return false;
    }
}

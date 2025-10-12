<?php
declare(strict_types=1);

/**
 * Copyright 2024, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2024, Cake Development Corporation (https://www.cakedc.com)
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\PHPStan\Rule\Controller;

use Cake\Controller\Controller;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Rule to enforce that certain controller methods must be used
 * (returned or assigned) to prevent unreachable code.
 */
class ControllerMethodMustBeUsedRule implements Rule
{
    /**
     * Methods that must be used (returned or assigned)
     *
     * @var array<string>
     */
    protected array $methodsRequiringUsage = [
        'render',
        'redirect',
    ];

    /**
     * @inheritDoc
     */
    public function getNodeType(): string
    {
        return Expression::class;
    }

    /**
     * @param \PhpParser\Node $node
     * @param \PHPStan\Analyser\Scope $scope
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        assert($node instanceof Expression);

        // Check if this is a method call
        if (!$node->expr instanceof MethodCall) {
            return [];
        }

        $methodCall = $node->expr;

        // Check if the method name is one we care about
        if (!$methodCall->name instanceof Node\Identifier) {
            return [];
        }

        $methodName = $methodCall->name->toString();
        if (!in_array($methodName, $this->methodsRequiringUsage, true)) {
            return [];
        }

        // Check if the method is being called on $this
        $callerType = $scope->getType($methodCall->var);
        if (!$callerType->isObject()->yes()) {
            return [];
        }

        // Check if it's a Controller class
        $classReflections = $callerType->getObjectClassReflections();
        $isController = false;
        foreach ($classReflections as $classReflection) {
            if ($classReflection->is(Controller::class)) {
                $isController = true;
                break;
            }
        }

        if (!$isController) {
            return [];
        }

        // If we reach here, it means the method call is wrapped in an Expression node
        // which means it's used as a statement (not returned or assigned)
        return [
            RuleErrorBuilder::message(sprintf(
                'Method `%s()` must be used to prevent unreachable code. ' .
                'Use `return $this->%s()` or assign it to a variable.',
                $methodName,
                $methodName,
            ))
            ->identifier('cake.controller.' . $methodName . 'MustBeUsed')
            ->build(),
        ];
    }
}

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

namespace CakeDC\PHPStan\Rule\Mailer;

use CakeDC\PHPStan\Rule\LoadObjectExistsCakeClassRule;
use CakeDC\PHPStan\Utility\CakeNameRegistry;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

class GetMailerExistsClassRule implements Rule
{
    /**
     * @var string
     */
    protected string $identifier = 'cake.getMailer.existClass';

    /**
     * @return string
     */
    public function getNodeType(): string
    {
        return MethodCall::class;
    }
    /**
     * @param \PhpParser\Node $node
     * @param \PHPStan\Analyser\Scope $scope
     * @return array<\PHPStan\Rules\RuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        assert($node instanceof MethodCall);
        if (!$node->name instanceof Node\Identifier
            || $node->name->name !== 'getMailer'
        ) {
            return [];
        }

        $args = $node->getArgs();
        if (!isset($args[0])) {
            return [];
        }
        $value = $args[0]->value;
        if (!$value instanceof String_) {
            return [];
        }
        $callerType = $scope->getType($node->var);
        $reflection = $callerType->getClassReflection();
        if ($reflection === null) {
            return [];
        }

        if (CakeNameRegistry::getMailerClassName($value->value)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Call to %s::%s could not find the class for "%s"',
                $reflection->getName(),
                $node->name->name,
                $value->value,
            ))
            ->identifier($this->identifier)
            ->build(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getTargetClassName(string $name): ?string
    {
        return CakeNameRegistry::getMailerClassName($name);
    }

    /**
     * @inheritDoc
     */
    protected function getDetails(string $reference, array $args): ?array
    {
        var_dump(['reference' => $reference]);
        if (str_ends_with($reference, 'Table')) {
            return [
                'alias' => $args[0] ?? null,
                'sourceMethods' => 'getMailer',
                'options' => [],
            ];
        }
        return null;
    }
}

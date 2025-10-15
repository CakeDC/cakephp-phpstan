<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Rule\Debug;

use Cake\Error\Debugger;
use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

class DisallowDebugStaticCallRule implements Rule
{
    /**
     * @var array<string, array<int, string>>
     */
    private array $disallowed = [
        //Methods must be lowercased
        Debugger::class => ['dump', 'printvar'],
        'DebugKit\DebugSql' => ['sql', 'sqld'],
    ];

    /**
     * @inheritDoc
     */
    public function getNodeType(): string
    {
        return StaticCall::class;
    }

    /**
     * @inheritDoc
     */
    public function processNode(Node $node, Scope $scope): array
    {
        assert($node instanceof StaticCall);
        if (!$node->class instanceof Name || !$node->name instanceof Identifier) {
            return [];
        }

        $className = (string)$node->class;
        if (!isset($this->disallowed[$className])) {
            return [];
        }
        $methodUsed = (string)$node->name;
        $method = strtolower($methodUsed);
        if (!in_array($method, $this->disallowed[$className], true)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Use of debug method "%s::%s" is not allowed. %s',
                $className,
                $methodUsed,
                'The use in shipped code is discouraged because they can leak sensitive information or clutter output.',
            ))
            ->identifier('cake.debug.debugStaticCallUse')
            ->build(),
        ];
    }
}

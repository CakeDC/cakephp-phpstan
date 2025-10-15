<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Rule\Debug;
use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

class DisallowDebugFunctionsRule implements Rule
{
    protected const BASIC = 'basic';
    protected const RETURNABLE = 'returnable';

    /**
     * @var array<string, string>
     */
    private array $disallowedFunctions = [
        'dd' => self::BASIC,
        'debug' => self::BASIC,
        'debug_print_backtrace' => self::BASIC,
        'debug_zval_dump' => self::BASIC,
        'pr' => self::BASIC,
        'print_r' => self::RETURNABLE,
        'stacktrace' => self::BASIC,
        'var_dump' => self::BASIC,
        'var_export' => self::RETURNABLE,
    ];

    /**
     * @inheritDoc
     */
    public function getNodeType(): string
    {
        return FuncCall::class;
    }

    /**
     * @param \PhpParser\Node $node
     * @param \PHPStan\Analyser\Scope $scope
     * @return array|\PHPStan\Rules\IdentifierRuleError[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        assert($node instanceof FuncCall);
        if (!$node->name instanceof Node\Name) {
            return [];
        }
        $usedName = $node->name->name;
        $name = strtolower($usedName);
        if (!isset($this->disallowedFunctions[$name])) {
            return [];
        }
        if ($this->disallowedFunctions[$name] === self::RETURNABLE) {
            $arg = $node->getArgs()[1]->value ?? null;
            if ($arg instanceof ConstFetch && $arg->name->name == 'true') {
                return [];
            }
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Use of debug function "%s" is not allowed',
                $usedName,
            ))
            ->identifier('cake.entity.arrayAccess')
            ->build(),
        ];
    }
}

<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Rule\Debug;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

class DisallowDebugFunctionsRule implements Rule
{
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
        return [];
    }
}

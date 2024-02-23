<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Visitor;

use CakeDC\PHPStan\Rule\Traits\ParseClassNameFromArgTrait;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\NodeVisitorAbstract;

class AddAssociationSetClassNameVisitor extends NodeVisitorAbstract
{
    use ParseClassNameFromArgTrait;

    public const ATTRIBUTE_NAME = 'addAssociationVisitorOptions';

    /**
     * @param \PhpParser\Node\Expr|null $optionsSet
     */
    public function __construct(protected ?Expr $optionsSet = null)
    {
    }

    /**
     * @param array<\PhpParser\Node> $nodes
     * @return array<\PhpParser\Node>|null
     */
    public function beforeTraverse(array $nodes): ?array
    {
        $this->optionsSet = null;

        return null;
    }

    /**
     * @param \PhpParser\Node $node
     * @return \PhpParser\Node|null
     */
    public function enterNode(Node $node): ?Node
    {
        if (!$node instanceof MethodCall || !$node->name instanceof Node\Identifier) {
            return null;
        }
        if ($this->optionsSet === null && $node->name->name === 'setClassName') {
            $this->optionsSet = $node->args[0]->value ?? null;
        }
        if (in_array($node->name->name, ['load', 'belongsTo', 'belongsToMany', 'hasOne', 'hasMany'])) {
            $node->setAttribute(self::ATTRIBUTE_NAME, $this->optionsSet);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function leaveNode(Node $node)
    {
        $this->optionsSet = null;

        return null;
    }
}

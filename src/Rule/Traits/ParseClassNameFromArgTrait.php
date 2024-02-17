<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Rule\Traits;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;

trait ParseClassNameFromArgTrait
{
    /**
     * @param \PhpParser\Node\Expr $value
     * @return string|null
     */
    protected function parseClassNameFromExprTrait(Expr $value): ?string
    {
        if ($value instanceof String_) {
            return $value->value;
        }

        if ($value instanceof ClassConstFetch) {
            assert($value->class instanceof Name);

            return $value->class->toString();
        }

        return null;
    }
}

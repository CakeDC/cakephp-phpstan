<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Traits;

use Cake\ORM\Association;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;

trait RepositoryReferenceTrait
{
    /**
     * @param \PHPStan\Analyser\Scope $scope
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     * @return string|null
     */
    protected function getReferenceClass(Scope $scope, MethodCall $methodCall): ?string
    {
        $classes = $scope->getType($methodCall->var)->getReferencedClasses();
        if (!isset($classes[0])) {
            return null;
        }
        if (!is_subclass_of($classes[0], Association::class)) {
            return $classes[0];
        }
        //We should have key 1 for associations, ex: BelongsTo<\App\Model\Table\UsersTable>

        return $classes[1] ?? null;
    }
}

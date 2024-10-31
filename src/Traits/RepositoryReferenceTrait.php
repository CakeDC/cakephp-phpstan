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
        $reflections = $scope->getType($methodCall->var)->getObjectClassReflections();
        if (!isset($reflections[0])) {
            return null;
        }
        if (!$reflections[0]->isSubclassOf(Association::class)) {
            return $reflections[0]->getName();
        }
        //We should have key 1 for associations, ex: BelongsTo<\App\Model\Table\UsersTable>
        if (isset($reflections[1])) {
            return $reflections[1]->getName();
        }

        return null;
    }
}

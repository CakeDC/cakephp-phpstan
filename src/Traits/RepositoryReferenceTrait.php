<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Traits;

use Cake\ORM\Association;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Association\HasOne;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;

trait RepositoryReferenceTrait
{
    /**
     * @var string[]
     */
    protected array $associationsClasses = [
        Association::class,
        BelongsTo::class,
        BelongsToMany::class,
        HasMany::class,
        HasOne::class,
    ];

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
        if (!in_array($classes[0], $this->associationsClasses)) {
            return $classes[0];
        }
        //We should have key 1 for associations, ex: BelongsTo<\App\Model\Table\UsersTable>

        return $classes[1] ?? null;
    }
}

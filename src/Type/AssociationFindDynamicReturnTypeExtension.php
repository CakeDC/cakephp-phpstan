<?php
declare(strict_types=1);

/**
 * Copyright 2025, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2025, Cake Development Corporation (https://www.cakedc.com)
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\PHPStan\Type;

use Cake\ORM\Association;
use Cake\ORM\Query\SelectQuery;
use CakeDC\PHPStan\Traits\EntityClassFromTableClassTrait;
use CakeDC\PHPStan\Traits\RepositoryReferenceTrait;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

/**
 * Narrows the return type of {@see Association::find()} to
 * `SelectQuery<TargetEntity>`.
 *
 * Cake core declares `Association::find()` as
 * `\Cake\ORM\Query\SelectQuery<EntityInterface|array>` — it does not propagate
 * the target table's `TEntity` template parameter. As a result, chains such as
 * `$this->Articles->Users->find()->first()` resolve to `EntityInterface|null`
 * instead of `User|null`, forcing every call-site to add inline `@var`
 * annotations.
 *
 * This extension reads the association's target table type — once
 * {@see \CakeDC\PHPStan\PhpDoc\TableAssociationTypeNodeResolverExtension} has
 * converted the intersection `BelongsTo&UsersTable` into the generic
 * `BelongsTo<UsersTable>` — derives the entity class via the standard CakePHP
 * naming convention, and replaces the return type with
 * `SelectQuery<UserEntity>`.
 *
 * Hydration-disabled queries are not detected here; PHPStan cannot follow
 * `$query->disableHydration()` calls regardless of which extension produces
 * the type, so narrowing to the entity is at least as accurate as the current
 * `EntityInterface|array` union and strictly better for the common hydrated
 * case.
 */
class AssociationFindDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    use EntityClassFromTableClassTrait;
    use RepositoryReferenceTrait;

    /**
     * @inheritDoc
     */
    public function getClass(): string
    {
        return Association::class;
    }

    /**
     * @inheritDoc
     */
    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'find';
    }

    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     * @param \PHPStan\Analyser\Scope $scope
     * @return \PHPStan\Type\Type|null
     */
    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope,
    ): ?Type {
        $tableClass = $this->getReferenceClass($scope, $methodCall);
        if ($tableClass === null) {
            return null;
        }

        $entityClass = $this->getEntityClassByTableClass($tableClass);
        if ($entityClass === null || !class_exists($entityClass)) {
            return null;
        }

        return new GenericObjectType(SelectQuery::class, [new ObjectType($entityClass)]);
    }
}

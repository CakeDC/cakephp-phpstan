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

namespace CakeDC\PHPStan\Test\TestCase\Type\Fake;

use App\Model\Table\NotesTable;
use function PHPStan\Testing\assertType;

/**
 * Exercises {@see \CakeDC\PHPStan\Type\AssociationFindDynamicReturnTypeExtension}.
 *
 * `Cake\ORM\Association::find()` is declared in Cake core as
 * `SelectQuery<EntityInterface|array>` — the target entity type is lost.
 * The extension restores it by reading the association's target table type
 * (provided by {@see \CakeDC\PHPStan\PhpDoc\TableAssociationTypeNodeResolverExtension})
 * and returning `SelectQuery<TargetEntity>`.
 *
 * Note that this fixture only asserts the SelectQuery template parameter,
 * not the downstream `->first()` / `->firstOrFail()` return type. Those depend
 * on Cake core's per-method `@return TSubject|null` / `@return TSubject`
 * annotations (cakephp/cakephp#19439, merged for 5.3.6). On older Cake
 * versions `first()` still returns `mixed`; the entity narrowing kicks in
 * automatically once consumers upgrade.
 */
class AssociationFindCorrectUsage
{
    public function narrowsSelectQueryGeneric(NotesTable $notes): void
    {
        // Sanity check: the existing TableAssociationTypeNodeResolverExtension
        // turns `BelongsTo&UsersTable` into the generic association type.
        assertType('Cake\ORM\Association\BelongsTo<App\Model\Table\UsersTable>', $notes->MyUsers);

        // This is what the new extension contributes: the SelectQuery is
        // templated with the target table's entity type instead of the
        // default `EntityInterface|array`.
        assertType(
            'Cake\ORM\Query\SelectQuery<App\Model\Entity\User>',
            $notes->MyUsers->find(),
        );
    }
}

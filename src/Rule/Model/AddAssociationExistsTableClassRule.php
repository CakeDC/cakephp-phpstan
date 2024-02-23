<?php
declare(strict_types=1);

/**
 * Copyright 2024, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2024, Cake Development Corporation (https://www.cakedc.com)
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\PHPStan\Rule\Model;

use Cake\ORM\AssociationCollection;
use CakeDC\PHPStan\Rule\LoadObjectExistsCakeClassRule;
use CakeDC\PHPStan\Utility\CakeNameRegistry;
use CakeDC\PHPStan\Visitor\AddAssociationSetClassNameVisitor;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;

class AddAssociationExistsTableClassRule extends LoadObjectExistsCakeClassRule
{
    /**
     * @var string
     */
    protected string $identifier = 'cake.addAssociation.existClass';

    /**
     * @var array<string>
     */
    protected array $tableSourceMethods = [
        'belongsTo',
        'belongsToMany',
        'hasMany',
        'hasOne',
    ];

    /**
     * @var array<string>
     */
    protected array $associationCollectionMethods = ['load'];

    /**
     * @inheritDoc
     */
    protected function getTargetClassName(string $name): ?string
    {
        return CakeNameRegistry::getTableClassName($name);
    }

    /**
     * @inheritDoc
     */
    protected function getDetails(string $reference, array $args): ?array
    {
        if (str_ends_with($reference, 'Table')) {
            return [
                'alias' => $args[0] ?? null,
                'options' => $args[1] ?? null,
                'sourceMethods' => $this->tableSourceMethods,
            ];
        }
        if ($reference === AssociationCollection::class) {
            return [
                'alias' => $args[1] ?? null,
                'options' => $args[2] ?? null,
                'sourceMethods' => $this->associationCollectionMethods,
            ];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    protected function getInputClassNameFromNode(MethodCall $node): ?string
    {
        $setClassNameValue = $node->getAttribute(AddAssociationSetClassNameVisitor::ATTRIBUTE_NAME);
        if ($setClassNameValue instanceof Expr) {
            return $this->parseClassNameFromExprTrait($setClassNameValue);
        }

        return null;
    }
}

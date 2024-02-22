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

use Cake\ORM\BehaviorRegistry;
use CakeDC\PHPStan\Rule\LoadObjectExistsCakeClassRule;
use CakeDC\PHPStan\Utility\CakeNameRegistry;

class AddBehaviorExistsClassRule extends LoadObjectExistsCakeClassRule
{
    /**
     * @var string
     */
    protected string $identifier = 'cake.addBehavior.existClass';

    /**
     * @var array<string>
     */
    protected array $tableSourceMethods = [
        'addBehavior',
    ];

    /**
     * @var array<string>
     */
    protected array $behaviorRegistryMethods = [
        'load',
    ];

    /**
     * @inheritDoc
     */
    protected function getTargetClassName(string $name): ?string
    {
        return CakeNameRegistry::getBehaviorClassName($name);
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
        if ($reference === BehaviorRegistry::class) {
            return [
                'alias' => $args[0] ?? null,
                'options' => $args[1] ?? null,
                'sourceMethods' => $this->behaviorRegistryMethods,
            ];
        }

        return null;
    }
}

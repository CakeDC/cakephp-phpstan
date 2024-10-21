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

namespace CakeDC\PHPStan\Rule\Controller;

use Cake\Controller\ComponentRegistry;
use CakeDC\PHPStan\Rule\LoadObjectExistsCakeClassRule;
use CakeDC\PHPStan\Utility\CakeNameRegistry;

class LoadComponentExistsClassRule extends LoadObjectExistsCakeClassRule
{
    /**
     * @var string
     */
    protected string $identifier = 'cake.loadComponent.existClass';

    /**
     * @var array<string>
     */
    protected array $sourceMethods = [
        'loadComponent',
    ];

    /**
     * @var array<string>
     */
    protected array $componentRegistryMethods = [
        'load',
    ];

    /**
     * @inheritDoc
     */
    protected function getTargetClassName(string $name): ?string
    {
        return CakeNameRegistry::getComponentClassName($name);
    }

    /**
     * @inheritDoc
     */
    protected function getDetails(string $reference, array $args): ?array
    {
        if (str_ends_with($reference, 'Controller')) {
            return [
                'alias' => $args[0] ?? null,
                'options' => $args[1] ?? null,
                'sourceMethods' => $this->sourceMethods,
            ];
        }
        if ($reference === ComponentRegistry::class) {
            return [
                'alias' => $args[0] ?? null,
                'options' => $args[1] ?? null,
                'sourceMethods' => $this->componentRegistryMethods,
            ];
        }

        return null;
    }
}

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

use CakeDC\PHPStan\Rule\LoadObjectExistsCakeClassRule;
use CakeDC\PHPStan\Utility\CakeNameRegistry;

class AddAssociationExistsTableClassRule extends LoadObjectExistsCakeClassRule
{
    /**
     * @var string
     */
    protected string $identifier = 'cake.addAssociation.existClass';

    /**
     * @var string
     */
    protected string $sourceClassSuffix = 'Table';

    /**
     * @var array<string>
     */
    protected array $sourceMethods = [
        'belongsTo',
        'belongsToMany',
        'hasMany',
        'hasOne',
    ];

    /**
     * @param string $name
     * @return string|null
     */
    protected function getTargetClassName(string $name): ?string
    {
        return CakeNameRegistry::getTableClassName($name);
    }
}

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

namespace CakeDC\PHPStan\Traits;

use Cake\Utility\Inflector;

/**
 * Resolves the entity class FQCN that belongs to a given table class FQCN,
 * using the standard CakePHP convention (`Foo\Model\Table\BarsTable` →
 * `Foo\Model\Entity\Bar`).
 */
trait EntityClassFromTableClassTrait
{
    /**
     * @param string $className Fully-qualified table class name.
     * @return string|null Fully-qualified entity class name, or null if the
     *  input does not match the `*\Model\Table\*Table` convention.
     */
    protected function getEntityClassByTableClass(string $className): ?string
    {
        $parts = explode('\\', $className);
        $count = count($parts);
        $nameIndex = $count - 1;
        $folderIndex = $count - 2;
        if ($count < 3 || $parts[$folderIndex] !== 'Table') {
            return null;
        }
        $name = str_replace('Table', '', $parts[$nameIndex]);
        $name = Inflector::singularize($name);
        $parts[$folderIndex] = 'Entity';
        $parts[$nameIndex] = $name;

        return implode('\\', $parts);
    }
}

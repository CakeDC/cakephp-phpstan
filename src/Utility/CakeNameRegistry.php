<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Utility;

use function Cake\Core\pluginSplit;

class CakeNameRegistry
{
    /**
     * @param string $baseName
     * @return array
     * @psalm-return array{string|null, string}
     */
    protected static function pluginSplit(string $baseName): array
    {
        return pluginSplit($baseName);
    }

    /**
     * @param string $baseName
     * @param array<string>|string $namespaceFormat
     * @return string|null
     */
    public static function getClassName(string $baseName, string|array $namespaceFormat): ?string
    {
        if (str_contains($baseName, '\\')) {
            return class_exists($baseName) ? $baseName : null;
        }

        [$plugin, $name] = static::pluginSplit($baseName);
        $prefixes = $plugin ? [$plugin] : ['App', 'Cake'];
        $namespaceFormat = (array)$namespaceFormat;
        foreach ($namespaceFormat as $format) {
            foreach ($prefixes as $prefix) {
                $namespace = str_replace('/', '\\', $prefix);
                $className = sprintf($format, $namespace, $name);
                if (class_exists($className)) {
                    return $className;
                }
            }
        }

        return null;
    }

    /**
     * @param string $name
     * @return string|null
     */
    public static function getComponentClassName(string $name): ?string
    {
        return static::getClassName($name, [
            '%s\\Controller\\Component\\%sComponent',
            '%s\\Controller\\Component\\%sComponent',
        ]);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public static function getBehaviorClassName(string $name): ?string
    {
        return static::getClassName($name, [
            '%s\\Model\\Behavior\\%sBehavior',
            '%s\\ORM\\Behavior\\%sBehavior',
        ]);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public static function getTableClassName(string $name): ?string
    {
        return static::getClassName($name, [
            '%s\\Model\\Table\\%sTable',
        ]);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public static function getMailerClassName(string $name): ?string
    {
        return static::getClassName($name, [
            '%s\\Mailer\\%sMailer',
        ]);
    }
}

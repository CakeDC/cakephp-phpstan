<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Utility;

use function Cake\Core\pluginSplit;

class CakeNameRegistry
{
    /**
     * @param string $appNamespace The application's namespace.
     */
    public function __construct(private readonly string $appNamespace)
    {
    }

    /**
     * @param string $appNamespace The application's namespace.
     */
    public static function instance(string $appNamespace = 'App'): self
    {
        return new self($appNamespace);
    }

    /**
     * @param string $baseName
     * @return array{string|null,string}
     * @psalm-return array{string|null,string}
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
    public function getClassName(string $baseName, string|array $namespaceFormat): ?string
    {
        if (str_contains($baseName, '\\')) {
            return class_exists($baseName) ? $baseName : null;
        }

        [$plugin, $name] = static::pluginSplit($baseName);
        $prefixes = $plugin !== null ? [$plugin] : [$this->appNamespace, 'Cake'];
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
    public function getComponentClassName(string $name): ?string
    {
        return $this->getClassName($name, [
            '%s\\Controller\\Component\\%sComponent',
            '%s\\Controller\\Component\\%sComponent',
        ]);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getBehaviorClassName(string $name): ?string
    {
        return $this->getClassName($name, [
            '%s\\Model\\Behavior\\%sBehavior',
            '%s\\ORM\\Behavior\\%sBehavior',
        ]);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getTableClassName(string $name): ?string
    {
        return $this->getClassName($name, [
            '%s\\Model\\Table\\%sTable',
        ]);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getMailerClassName(string $name): ?string
    {
        return $this->getClassName($name, [
            '%s\\Mailer\\%sMailer',
        ]);
    }
}

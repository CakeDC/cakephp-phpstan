<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Traits;

use PHPStan\Reflection\ClassReflection;

trait IsFromTargetTrait
{
    /**
     * @param \PHPStan\Reflection\ClassReflection $reflection
     * @return bool
     */
    protected function isFromTargetTrait(ClassReflection $reflection, string $targetTrait): bool
    {
        foreach ($reflection->getTraits() as $trait) {
            if ($trait->getName() === $targetTrait) {
                return true;
            }
        }
        foreach ($reflection->getParents() as $parent) {
            if ($this->isFromTargetTrait($parent, $targetTrait)) {
                return true;
            }
        }

        return false;
    }
}

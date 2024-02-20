<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Rule\Model;

class TableGetMatchOptionsTypesRule extends OrmSelectQueryFindMatchOptionsTypesRule
{

    /**
     * @var array<string>
     */
    protected array $targetMethods = ['get'];

    /**
     * @inheritDoc
     */
    protected function getDetails(array $referenceClasses, string $methodName, array $args): ?array
    {
        $reference = $this->getReference($referenceClasses);
        if (str_ends_with($reference, 'Table') || in_array($reference, $this->associationTypes)) {
            $lastOptionPosition = 4;
            $options = $this->getOptions($args, $lastOptionPosition);

            return [
                'options' => $options,
                'reference' => $reference,
                'methodName' => $methodName,
            ];
        }

        return null;
    }
}

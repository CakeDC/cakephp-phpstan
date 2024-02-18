<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Rule\Model;

class TableGetMatchOptionsTypesRule extends OrmSelectQueryFindMatchOptionsTypesRule
{
    /**
     * @param string $reference
     * @param string $methodName
     * @param array<\PhpParser\Node\Arg> $args
     * @return array{'options': array<\PhpParser\Node\Expr>, 'reference':string, 'methodName':string}|null
     */
    protected function getDetails(string $reference, string $methodName, array $args): ?array
    {
        if (str_ends_with($reference, 'Table') && $methodName === 'get') {
            $lastOptionPosition = 4;
            $argNamesIgnore = ['primaryKey', 'finder', 'cache', 'cacheKey'];
            $options = $this->getOptions($args, $lastOptionPosition, $argNamesIgnore);

            return [
                'options' => $options,
                'reference' => $reference,
                'methodName' => $methodName,
            ];
        }

        return null;
    }
}

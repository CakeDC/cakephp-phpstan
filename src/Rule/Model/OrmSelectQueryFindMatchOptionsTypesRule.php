<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Rule\Model;

use Cake\ORM\Association;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Association\HasOne;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use CakeDC\PHPStan\Rule\Traits\ParseClassNameFromArgTrait;
use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Php\PhpMethodReflection;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;

class OrmSelectQueryFindMatchOptionsTypesRule implements Rule
{
    use ParseClassNameFromArgTrait;

    /**
     * @var \PHPStan\Rules\RuleLevelHelper
     */
    protected RuleLevelHelper $ruleLevelHelper;
    /**
     * @var array<string>
     */
    protected array $targetMethods = ['find'];
    /**
     * @var array<string>
     */
    protected array $queryOptionsMap = [
        'select' => 'select',
        'fields' => 'select',
        'conditions' => 'where',
        'where' => 'where',
        'join' => 'join',
        'order' => 'orderBy',
        'orderBy' => 'orderBy',
        'limit' => 'limit',
        'offset' => 'offset',
        'group' => 'groupBy',
        'groupBy' => 'groupBy',
        'having' => 'having',
        'contain' => 'contain',
        'page' => 'page',
    ];
    /**
     * @var array<string>
     */
    protected array $associationTypes = [
        BelongsTo::class,
        BelongsToMany::class,
        HasMany::class,
        HasOne::class,
        Association::class,
    ];

    /**
     * @param \PHPStan\Rules\RuleLevelHelper $ruleLevelHelper
     */
    public function __construct(RuleLevelHelper $ruleLevelHelper)
    {
        $this->ruleLevelHelper = $ruleLevelHelper;
    }

    /**
     * @return string
     */
    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param \PhpParser\Node $node
     * @param \PHPStan\Analyser\Scope $scope
     * @return array<\PHPStan\Rules\RuleError>
     * @throws \PHPStan\ShouldNotHappenException
     * @throws \PHPStan\Reflection\MissingMethodFromReflectionException
     */
    public function processNode(Node $node, Scope $scope): array
    {
        assert($node instanceof MethodCall);
        $args = $node->getArgs();
        if (!$node->name instanceof Node\Identifier || !in_array($node->name->name, $this->targetMethods)) {
            return [];
        }
        $referenceClasses = $scope->getType($node->var)->getReferencedClasses();
        if (empty($referenceClasses)) {
            return [];
        }
        $details = $this->getDetails($referenceClasses, $node->name->name, $args);
        if ($details === null) {
            return [];
        }
        $specificFinderOptions = $this->getSpecificFinderOptions($details, $scope);
        if (empty($details['options'])) {
            return $this->checkMissingRequiredOptions($specificFinderOptions, $details, []);
        }
        $paramNamesIgnore = $this->getParamNamesIgnore($scope, $node);
        $errors = [];
        foreach ($details['options'] as $name => $item) {
            if (in_array($name, $paramNamesIgnore)) {
                continue;
            }
            $parameterType = $this->getExpectedType($name, $scope, $specificFinderOptions);
            $error = $parameterType ? $this->processPropertyTypeCheck(
                $parameterType,
                $scope->getType($item),
                $details,
                $name
            ) : null;

            if ($error) {
                $errors[] = $error;
            }
        }

        return $this->checkMissingRequiredOptions($specificFinderOptions, $details, $errors);
    }

    /**
     * @param array<string> $referenceClasses
     * @param string $methodName
     * @param array<\PhpParser\Node\Arg> $args
     * @return array{'options': array<\PhpParser\Node\Expr>, 'reference':string, 'methodName':string, 'finder': string|null}|null
     */
    protected function getDetails(array $referenceClasses, string $methodName, array $args): ?array
    {
        $reference = $this->getReference($referenceClasses);
        if (
            str_ends_with($reference, 'Table')
            || $reference === SelectQuery::class
            || in_array($reference, $this->associationTypes)
        ) {
            $lastOptionPosition = 1;
            $finder = 'all';
            if (isset($args[0]) && $args[0]->value instanceof String_) {
                $finder = $args[0]->value->value;
            }
            $options = $this->getOptions($args, $lastOptionPosition);

            return [
                'finder' => $finder,
                'options' => $options,
                'reference' => $reference,
                'methodName' => $methodName,
            ];
        }

        return null;
    }

    /**
     * @param \PHPStan\Type\Type $inputType
     * @param \PHPStan\Type\Type $assignedValueType
     * @param array{'reference':string, 'methodName':string} $details
     * @param string $property
     * @return \PHPStan\Rules\RuleError|null
     * @throws \PHPStan\ShouldNotHappenException
     */
    protected function processPropertyTypeCheck(
        Type $inputType,
        Type $assignedValueType,
        array $details,
        string $property
    ): ?RuleError {
        $accepts = $this->ruleLevelHelper->acceptsWithReason($inputType, $assignedValueType, true);//@phpstan-ignore-line

        if ($accepts->result) {
            return null;
        }
        $propertyDescription = sprintf(
            'Call to %s::%s with option "%s"',
            $details['reference'],
            $details['methodName'],
            $property
        );
        $verbosityLevel = VerbosityLevel::getRecommendedLevelByType($inputType, $assignedValueType);

        return RuleErrorBuilder::message(
            sprintf(
                '%s (%s) does not accept %s.',
                $propertyDescription,
                $inputType->describe($verbosityLevel),
                $assignedValueType->describe($verbosityLevel)
            )
        )
            ->acceptsReasonsTip($accepts->reasons)
            ->identifier('cake.tableGetMatchOptionsTypes.invalidType')
            ->build();
    }

    /**
     * @param \PHPStan\Analyser\Scope $scope
     * @param string $targetMethod
     * @return \PHPStan\Reflection\Php\PhpMethodReflection
     * @throws \PHPStan\Reflection\MissingMethodFromReflectionException
     */
    protected function getTargetMethod(Scope $scope, string $targetMethod): PhpMethodReflection
    {
        $object = new ObjectType(SelectQuery::class);
        $classReflection = $object->getClassReflection();
        assert($classReflection instanceof ClassReflection);
        $methodReflection = $classReflection
            ->getMethod($targetMethod, $scope);
        assert($methodReflection instanceof PhpMethodReflection);

        return $methodReflection;
    }

    /**
     * @param \PhpParser\Node\Arg $arg
     * @param array<\PhpParser\Node\Expr> $options
     * @return array<\PhpParser\Node\Expr>
     */
    protected function extractOptionsUnpackedArray(Node\Arg $arg, array $options): array
    {
        if ($arg->value instanceof Array_ && $arg->unpack) {
            $options = $this->getOptionsFromArray($arg->value, $options);
        }

        return $options;
    }

    /**
     * @param array<\PhpParser\Node\Arg> $args
     * @return array<\PhpParser\Node\Expr>
     */
    protected function getOptions(array $args, int $optionsArgPosition): array
    {
        $lastArgPos = $optionsArgPosition;
        $totalArgsMethod = $optionsArgPosition + 1;
        $options = [];
        if (
            count($args) === $totalArgsMethod
            && $args[$lastArgPos]->value instanceof Array_
            && !$args[$lastArgPos]->name
            && $args[$lastArgPos]->unpack !== true
        ) {
            return $this->getOptionsFromArray($args[$lastArgPos]->value, $options);
        }
        foreach ($args as $arg) {
            if ($arg->name) {
                $options[$arg->name->name] = $arg->value;
            }
            $options = $this->extractOptionsUnpackedArray($arg, $options);
        }

        return $options;
    }

    /**
     * @param \PhpParser\Node\Expr\Array_ $source
     * @param array<\PhpParser\Node\Expr> $options
     * @return array<\PhpParser\Node\Expr>
     */
    protected function getOptionsFromArray(Array_ $source, array $options): array
    {
        foreach ($source->items as $item) {
            if (isset($item->key) && $item->key instanceof String_) {
                $options[$item->key->value] = $item->value;
            }
        }

        return $options;
    }

    /**
     * @param \PHPStan\Analyser\Scope $scope
     * @param \PhpParser\Node\Expr\MethodCall $node
     * @return array<string>
     */
    protected function getParamNamesIgnore(Scope $scope, MethodCall $node): array
    {
        assert($node->name instanceof Node\Identifier);
        $methodReflection = $scope->getMethodReflection($scope->getType($node->var), $node->name->toString());
        if ($methodReflection === null) {
            return [];
        }

        $parameters = $methodReflection
            ->getVariants()[0]->getParameters();
        $names = [];
        foreach ($parameters as $parameter) {
            if (!$parameter->isVariadic()) {
                $names[] = $parameter->getName();
            }
        }

        return $names;
    }

    /**
     * @param array<string> $referenceClasses
     * @return string
     */
    protected function getReference(array $referenceClasses): string
    {
        $reference = $referenceClasses[0];
        //Is association generic? ex: Cake\ORM\Association\BelongsTo<App\Model\Table\UsersTable>
        if (isset($referenceClasses[1]) && in_array($reference, $this->associationTypes)) {
            $reference = $referenceClasses[1];
        }

        return $reference;
    }

    /**
     * @param array{'options': array<\PhpParser\Node\Expr>, 'reference':string, 'methodName':string, 'finder': string|null} $details
     * @param \PHPStan\Analyser\Scope $scope
     * @return array<string, \PHPStan\Reflection\ParameterReflectionWithPhpDocs>
     */
    protected function getSpecificFinderOptions(array $details, Scope $scope): array
    {
        $specificFinderOptions = [];
        $finder = $details['finder'];
        if (!$finder || $finder === 'all') {
            return [];
        }
        $tableClass = $details['reference'];
        if (!str_ends_with($details['reference'], 'Table')) {
            $tableClass = Table::class;
        }
        $object = new ObjectType($tableClass);
        $finderMethod = 'find' . $finder;
        if ($object->hasMethod($finderMethod)->no()) {
            return [];
        }
        $method = $object->getMethod($finderMethod, $scope);
        $parameters = $method->getVariants()[0]->getParameters();
        if (!isset($parameters[1])) {
            return [];
        }
        foreach ($parameters as $key => $param) {
            if ($key === 0) {
                continue;
            }
            $specificFinderOptions[$param->getName()] = $param;
        }

        return $specificFinderOptions;
    }

    /**
     * @param string|int $name
     * @param \PHPStan\Analyser\Scope $scope
     * @param array<string, \PHPStan\Reflection\ParameterReflectionWithPhpDocs> $specificFinderOptions
     * @return \PHPStan\Type\Type|null
     * @throws \PHPStan\Reflection\MissingMethodFromReflectionException
     */
    protected function getExpectedType(int|string $name, Scope $scope, array $specificFinderOptions): ?Type
    {
        if (isset($this->queryOptionsMap[$name])) {
            $methodReflection = $this->getTargetMethod($scope, $this->queryOptionsMap[$name]);
            $parameter = $methodReflection->getVariants()[0]->getParameters()[0];

            return $parameter->getType();
        }

        if (isset($specificFinderOptions[$name])) {
            return $specificFinderOptions[$name]->getType();
        }

        return null;
    }

    /**
     * @param array<string, \PHPStan\Reflection\ParameterReflectionWithPhpDocs> $specificFinderOptions
     * @param array{'options': array<\PhpParser\Node\Expr>, 'reference':string, 'methodName':string, 'finder': string|null} $details
     * @param array<\PHPStan\Rules\RuleError> $errors
     * @return array<\PHPStan\Rules\RuleError>
     * @throws \PHPStan\ShouldNotHappenException
     */
    protected function checkMissingRequiredOptions(array $specificFinderOptions, array $details, array $errors): array
    {
        foreach ($specificFinderOptions as $requireOptionName => $parameter) {
            if (!$parameter->isOptional() && !array_key_exists($requireOptionName, $details['options'])) {
                $errorMessage = sprintf(
                    'Call to %s::%s is missing required finder option "%s".',
                    $details['reference'],
                    $details['methodName'],
                    $requireOptionName
                );
                $errors[] = RuleErrorBuilder::message($errorMessage)
                    ->identifier('cake.tableGetMatchOptionsTypes.invalidType')
                    ->build();
            }
        }

        return $errors;
    }
}

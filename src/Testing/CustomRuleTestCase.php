<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Testing;

use PHPStan\Testing\RuleTestCase;

abstract class CustomRuleTestCase extends RuleTestCase
{
    use AnalyseCheckLineStartsWithTrait;
}

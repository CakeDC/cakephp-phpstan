<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Type;

use PHPStan\Testing\TypeInferenceTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class TypeInferenceTest extends TypeInferenceTestCase
{
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../../phpstan.neon'];
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function provideTypes(): array
    {
        return self::gatherAssertTypes(__DIR__ . '/../../data/registry-types.php');
    }

    /**
     * @return void
     */
    public function testFixtureIsNotLoadedAtRuntime(): void
    {
        static::assertFalse(class_exists('StaticAnalysisFixture\\StaticOnlyTable', false));
    }

    #[DataProvider('provideTypes')]
    public function testTypes(string $assertType, string $file, mixed ...$args): void
    {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }
}

<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

class ArrayOfStringStartsWith extends Constraint
{
    /**
     * @var array<string>
     */
    private readonly array $actual;
    /**
     * @var array<array{expected: string, type: string, actual: string|null}>
     */
    private array $result = [];
    /**
     * @var array<string>
     */
    private array $notExpected = [];

    /**
     * @param array<string> $actual
     */
    public function __construct(array $actual)
    {
        $this->actual = $actual;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return 'a list of errors';
    }

    /**
     * @param mixed $other
     * @return bool
     */
    protected function matches(mixed $other): bool
    {
        $result = true;
        $this->notExpected = $this->actual;
        assert(is_array($other));
        foreach ($other as $key => $error) {
            if (!isset($this->actual[$key])) {
                $this->result[$key] = ['expected' => $error, 'type' => 'missing', 'actual' => null];
                $result = false;
                continue;
            }
            unset($this->notExpected[$key]);
            if (!str_starts_with($this->actual[$key], $error)) {
                $this->result[$key] = ['expected' => $error, 'type' => 'not-equal', 'actual' => $this->actual[$key]];
                $result = false;
            }
        }

        return $result && empty($this->notExpected);
    }

    /**
     * @param mixed $other
     * @return string
     */
    protected function failureDescription(mixed $other): string
    {
        $text = "\n";
        foreach ($this->result as $item) {
            if ($item['type'] === 'not-equal') {
                $text .= sprintf(" -%s \n +%s \n", $item['expected'], $item['actual']);
            }
            if ($item['type'] === 'missing') {
                $text .= sprintf(" -%s \n", $item['expected']);
            }
        }

        foreach ($this->notExpected as $item) {
            $text .= sprintf(" \n +%s", $item);
        }

        return $text;
    }
}

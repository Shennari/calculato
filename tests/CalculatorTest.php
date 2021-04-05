<?php

declare(strict_types=1);

namespace Tests;

class CalculatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProvider
     * @param string $expression
     * @param float $expected
     */
    public function testCalculate(string $expression, float $expected): void
    {
        $calculator = new \App\Calculator();

        $this->assertEquals($expected, $calculator->calculate($expression));
    }

    public function dataProvider(): array
    {
        return [
            '1 + 1' => [
                '1 + 1',
                2
            ], '1 + 2 - 1' => [
                '1 + 2 - 1',
                2
            ], '1 + 2 * 2' => [
                '1 + 2 * 2',
                5
            ]
        ];
    }
}
<?php

declare(strict_types=1);

namespace tests\Service;

use PHPUnit\Framework\TestCase;
use src\Service\Math;
use InvalidArgumentException;

class MathTest extends TestCase
{
    private Math $math;

    protected function setUp(): void
    {
        $this->math = new Math(2);
    }

    /**
     * @dataProvider dataProviderForAddTesting
     */
    public function testAdd(string $leftOperand, string $rightOperand, string $expected): void
    {
        $result = $this->math->add($leftOperand, $rightOperand);
        self::assertSame($expected, $result);
    }

    /**
     * @dataProvider dataProviderForSubtractTesting
     */
    public function testSubtract(string $leftOperand, string $rightOperand, string $expected): void
    {
        $result = $this->math->subtract($leftOperand, $rightOperand);
        self::assertSame($expected, $result);
    }

    /**
     * @dataProvider dataProviderForMultiplyTesting
     */
    public function testMultiply(string $leftOperand, string $rightOperand, string $expected): void
    {
        $result = $this->math->multiply($leftOperand, $rightOperand);
        self::assertSame($expected, $result);
    }

    /**
     * @dataProvider dataProviderForDivideTesting
     */
    public function testDivide(string $leftOperand, string $rightOperand, string $expected): void
    {
        $result = $this->math->divide($leftOperand, $rightOperand);
        self::assertSame($expected, $result);
    }

    /**
     * @dataProvider dataProviderForInvalidDivision
     */
    public function testInvalidDivision(string $leftOperand, string $rightOperand): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Division by zero is not allowed.');
        $this->math->divide($leftOperand, $rightOperand);
    }

    public static function dataProviderForAddTesting(): array
    {
        return [
            'add 2 natural numbers' => ['1.00', '2.00', '3.00'],
            'add negative number to a positive' => ['-1', '2.00', '1.00'],
            'add natural number to a float' => ['1', '1.05', '2.05'],
        ];
    }

    public static function dataProviderForSubtractTesting(): array
    {
        return [
            'subtract 2 natural numbers' => ['2.00', '1.00', '1.00'],
            'subtract negative number from a positive' => ['2', '-1.00', '3.00'],
            'subtract float from natural number' => ['2', '0.05', '1.95'],
        ];
    }

    public static function dataProviderForMultiplyTesting(): array
    {
        return [
            'multiply 2 natural numbers' => ['2.00', '3.00', '6.00'],
            'multiply a negative and a positive number' => ['-2', '3.00', '-6.00'],
            'multiply float by natural number' => ['2', '0.05', '0.10'],
        ];
    }

    public static function dataProviderForDivideTesting(): array
    {
        return [
            'divide 2 natural numbers' => ['6.00', '2.00', '3.00'],
            'divide a negative by a positive number' => ['-6', '2.00', '-3.00'],
            'divide natural number by float' => ['2', '0.50', '4.00'],
        ];
    }

    public static function dataProviderForInvalidDivision(): array
    {
        return [
            'divide by zero' => ['1', '0'],
        ];
    }
}

<?php

declare(strict_types=1);

namespace src\Service;

use InvalidArgumentException;

class Math
{
    private int $scale;

    public function __construct(int $scale = 2)
    {
        $this->scale = $scale;
    }

    public function add(string $leftOperand, string $rightOperand): string
    {
        return bcadd($leftOperand, $rightOperand, $this->scale);
    }

    public function subtract(string $leftOperand, string $rightOperand): string
    {
        return bcsub($leftOperand, $rightOperand, $this->scale);
    }

    public function multiply(string $leftOperand, string $rightOperand): string
    {
        return bcmul($leftOperand, $rightOperand, $this->scale);
    }

    public function divide(string $leftOperand, string $rightOperand): string
    {
        if ($this->isEqual($rightOperand, '0')) {
            throw new InvalidArgumentException('Division by zero is not allowed.');
        }
        return bcdiv($leftOperand, $rightOperand, $this->scale);
    }

    public function isEqual(string $leftOperand, string $rightOperand): bool
    {
        return bccomp($leftOperand, $rightOperand, $this->scale) === 0;
    }

    public function isGreaterThan(string $leftOperand, string $rightOperand): bool
    {
        return bccomp($leftOperand, $rightOperand, $this->scale) === 1;
    }

    public function isGreaterThanOrEqualTo(string $leftOperand, string $rightOperand): bool
    {
        $result = bccomp($leftOperand, $rightOperand, $this->scale);
        return $result === 1 || $result === 0;
    }

    public function isLessThanOrEqualTo(string $leftOperand, string $rightOperand): bool
    {
        $result = bccomp($leftOperand, $rightOperand, $this->scale);
        return $result === -1 || $result === 0;
    }
}

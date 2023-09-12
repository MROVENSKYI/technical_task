<?php

declare(strict_types=1);

namespace src\Service;

use src\Model\Transaction;
use src\Strategy\Factory;

class CommissionCalculator
{
    private Factory $strategy;

    public function __construct(Factory $strategy)
    {
        $this->strategy = $strategy;
    }

    public function calculate(Transaction $transaction): array
    {
        $strategy = $this->strategy->getStrategy($transaction);

        return $strategy->calculate($transaction);
    }
}
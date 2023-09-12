<?php

declare(strict_types=1);

namespace src\Strategy;

use src\Strategy\Interface\CommissionStrategyInterface;
use src\Model\Transaction;

class Factory
{
    private array $strategies = [];

    public function __construct(CommissionStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    public function getStrategy(Transaction $transaction): ?CommissionStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->canApply($transaction)) {
                return $strategy;
            }
        }
        return null;
    }
}

<?php

declare(strict_types=1);

namespace src\Strategy;

use src\Config\FileConstants;
use src\Model\Transaction;
use src\Service\Math;
use src\Strategy\Interface\CommissionStrategyInterface;

class DepositCommissionStrategy implements CommissionStrategyInterface
{
    private Math $mathService;

    public function __construct(
        Math $mathService
    )
    {
        $this->mathService = $mathService;
    }

    public function canApply(Transaction $transaction): bool
    {
        return $transaction->getOperationType() === 'deposit';
    }

    public function calculate(Transaction $transaction): array
    {
        $commissionAmount = $this->mathService->multiply($transaction->getAmount(), FileConstants::DEPOSIT_FEE);
        return [
            'amount' => $commissionAmount,
            'currency' => $transaction->getCurrency()
        ];
    }
}
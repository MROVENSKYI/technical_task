<?php

declare(strict_types=1);

namespace src\Strategy;

use src\Config\FileConstants;
use src\Model\Transaction;
use src\Service\Math;
use src\Strategy\Interface\CommissionStrategyInterface;

class BusinessWithdrawStrategy implements CommissionStrategyInterface
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
        return $transaction->getOperationType() === 'withdraw' && $transaction->getUserType() === 'business';
    }

    public function calculate(Transaction $transaction): array
    {
        $commissionAmount = $this->mathService->multiply($transaction->getAmount(), FileConstants::BUSINESS_WITHDRAW_FEE);

        return [
            'amount' => $commissionAmount,
            'currency' => $transaction->getCurrency()
        ];
    }
}
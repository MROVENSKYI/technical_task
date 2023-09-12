<?php

declare(strict_types=1);

namespace src\Strategy;

use Exception;
use src\Config\FileConstants;
use src\Model\Transaction;
use src\Repository\TransactionRepository;
use src\Service\CurrencyConverter;
use src\Service\Math;
use src\Strategy\Interface\CommissionStrategyInterface;

class PrivateWithdrawalStrategy implements CommissionStrategyInterface
{
    private TransactionRepository $transactionTracker;
    private CurrencyConverter $currencyConverter;
    private Math $mathService;

    public function __construct(TransactionRepository $transactionTracker, CurrencyConverter $currencyConverter, Math $mathService)
    {
        $this->transactionTracker = $transactionTracker;
        $this->currencyConverter = $currencyConverter;
        $this->mathService = $mathService;
    }

    public function canApply(Transaction $transaction): bool
    {
        return $transaction->getOperationType() === 'withdraw' && $transaction->getUserType() === 'private';
    }

    /**
     * @throws Exception
     */
    public function calculate(Transaction $transaction): array
    {
        $commissionInEUR = $this->calculateCommissionInEUR($transaction);

        $commissionInOperationCurrency = $this->convertCommissionToTransactionCurrency($commissionInEUR, $transaction->getCurrency());

        return [
            'amount' => $commissionInOperationCurrency,
            'currency' => $transaction->getCurrency()
        ];
    }

    /**
     * @throws Exception
     */
    private function calculateCommissionInEUR(Transaction $transaction): string
    {
        $amountInEUR = $this->currencyConverter->convert($transaction->getAmount(), $transaction->getCurrency(), 'EUR');
        $withdrawalsCount = $this->transactionTracker->getWeeklyCount($transaction->getUserId(), $transaction->getDate());
        $weeklyTransactionSum = $this->transactionTracker->getWeeklyAmount($transaction->getUserId(), $transaction->getDate());
        $totalWithdrawalForWeek = $this->mathService->add($amountInEUR, $weeklyTransactionSum);
        if ($this->mathService->isGreaterThanOrEqualTo((string)$withdrawalsCount, FileConstants::FREE_WITHDRAWALS_LIMIT)) {

            return $this->mathService->multiply($amountInEUR, FileConstants::PRIVATE_COMMISSION_RATE);
        }
        if ($this->mathService->isLessThanOrEqualTo($totalWithdrawalForWeek, FileConstants::FREE_WITHDRAWAL_AMOUNT_LIMIT)) {

            return '0.00';
        }
        $exceededAmountInEUR = $this->mathService->subtract(
            $totalWithdrawalForWeek,
            FileConstants::FREE_WITHDRAWAL_AMOUNT_LIMIT
        );

        $sumToTakeFee = min($exceededAmountInEUR, $amountInEUR);
        return $this->mathService->multiply($sumToTakeFee, FileConstants::PRIVATE_COMMISSION_RATE);
    }

    /**
     * @throws Exception
     */
    private function convertCommissionToTransactionCurrency(string $commissionInEUR, string $currency): string
    {
        if ($currency !== 'EUR') {
            return $this->currencyConverter->convert($commissionInEUR, 'EUR', $currency);
        }

        return $commissionInEUR;
    }
}
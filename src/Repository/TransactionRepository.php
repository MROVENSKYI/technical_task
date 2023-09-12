<?php

declare(strict_types=1);

namespace src\Repository;

use DateTime;
use Exception;
use src\Model\Transaction;
use src\Service\CurrencyConverter;
use src\Service\Math;

class TransactionRepository
{
    private array $transactions = [];
    private CurrencyConverter $currencyConverter;
    private Math $mathService;

    public function __construct(CurrencyConverter $currencyConverter, Math $mathService)
    {
        $this->currencyConverter = $currencyConverter;
        $this->mathService = $mathService;
    }

    /**
     * @throws Exception
     */
    public function addTransaction(Transaction $transaction): void
    {
        $this->transactions[] = $transaction;
    }

    public function getWeeklyCount(int $userId, DateTime $date): int
    {
        [$startOfWeek, $endOfWeek] = $this->getStartAndEndOfWeek($date);

        return array_reduce(
            $this->transactions, fn($result, $transaction) => (
            $transaction->getUserId() === $userId &&
            $transaction->getDate() >= $startOfWeek &&
            $transaction->getDate() <= $endOfWeek &&
            $transaction->getOperationType() === 'withdraw'
        ) ? $result + 1 : $result, 0
        );
    }

    public function getWeeklyAmount(int $userId, DateTime $date): string
    {
        [$startOfWeek, $endOfWeek] = $this->getStartAndEndOfWeek($date);

        return array_reduce(
            $this->transactions, function ($result, $transaction) use ($userId, $startOfWeek, $endOfWeek) {
            if ($transaction->getUserId() !== $userId
                || $transaction->getDate() < $startOfWeek
                || $transaction->getDate() > $endOfWeek
                || $transaction->getOperationType() !== 'withdraw'
            ) {
                return $result;
            }

            try {
                $convertedAmount = $this->currencyConverter->convert($transaction->getAmount(), $transaction->getCurrency(), 'EUR');
            } catch (Exception $e) {
                return sprintf("Error: %s%s", $e->getMessage(), PHP_EOL);
            }

            return $this->mathService->add($result, $convertedAmount);
        }, '0.00'
        );
    }

    private function getStartAndEndOfWeek(DateTime $date): array
    {
        $startOfWeek = clone $date;
        $startOfWeek->modify('Monday this week');

        $endOfWeek = clone $date;
        $endOfWeek->modify('Sunday this week');

        return [$startOfWeek, $endOfWeek];
    }
}
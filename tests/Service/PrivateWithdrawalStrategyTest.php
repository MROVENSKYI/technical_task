<?php

declare(strict_types=1);

namespace tests\Service;

use DateTime;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use src\Model\Transaction;
use src\Repository\TransactionRepository;
use src\Service\CurrencyConverter;
use src\Service\Math;
use src\Strategy\PrivateWithdrawalStrategy;

class PrivateWithdrawalStrategyTest extends TestCase
{
    private PrivateWithdrawalStrategy $strategy;

    private MockObject|TransactionRepository $transactionTracker;
    private CurrencyConverter|MockObject $currencyConverter;
    private MockObject|Math $mathService;

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->transactionTracker = $this->createMock(TransactionRepository::class);
        $this->currencyConverter = $this->createMock(CurrencyConverter::class);
        $this->mathService = $this->createMock(Math::class);

        $this->strategy = new PrivateWithdrawalStrategy($this->transactionTracker, $this->currencyConverter, $this->mathService);
    }

    public function testCanApplyToWithdrawalForPrivateUser(): void
    {
        $transaction = new Transaction(new DateTime(), 1, 'private', 'withdraw', "100", 'USD');
        $this->assertTrue($this->strategy->canApply($transaction));
    }

    public function testCannotApplyToDepositForPrivateUser(): void
    {
        $transaction = new Transaction(new DateTime(), 1, 'private', 'deposit', "100", 'EUR');
        $this->assertFalse($this->strategy->canApply($transaction));
    }

    /**
     * @throws Exception
     */
    public function testCalculateFeeForSingleTransactionExceedingFreeLimit(): void
    {
        $transaction = new Transaction(new DateTime(), 1, 'private', 'withdraw', "1000", 'EUR');

        $this->mockDependenciesForSingleTransactionExceedingFreeLimit();

        $result = $this->strategy->calculate($transaction);

        $this->assertSame('3', $result['amount']);
        $this->assertSame('EUR', $result['currency']);
    }

    private function mockDependenciesForSingleTransactionExceedingFreeLimit(): void
    {
        $this->transactionTracker->method('getWeeklyCount')->willReturn(4);
        $this->transactionTracker->method('getWeeklyAmount')->willReturn("1500");
        $this->currencyConverter->method('convert')->willReturn("1000");
        $this->mathService->method('multiply')->willReturn("3");
        $this->mathService->method('subtract')->willReturn("500");
    }

    /**
     * @throws Exception
     */
    public function testCalculateFeeForMultipleTransactions(): void
    {
        $this->mockDependenciesForMultipleTransactions();

        $transaction1 = new Transaction(new DateTime(), 1, 'private', 'withdraw', "300", 'EUR');
        $result1 = $this->strategy->calculate($transaction1);
        $this->assertSame('3', $result1['amount']);

        $transaction2 = new Transaction(new DateTime(), 1, 'private', 'withdraw', "500", 'EUR');
        $result2 = $this->strategy->calculate($transaction2);
        $this->assertSame('3', $result2['amount']);

        $transaction3 = new Transaction(new DateTime(), 1, 'private', 'withdraw', "1200", 'EUR');
        $result3 = $this->strategy->calculate($transaction3);
        $this->assertSame('3', $result3['amount']);
    }

    private function mockDependenciesForMultipleTransactions(): void
    {
        $this->transactionTracker->method('getWeeklyCount')->willReturnOnConsecutiveCalls(2, 3, 4);
        $this->transactionTracker->method('getWeeklyAmount')->willReturnOnConsecutiveCalls("800", "1300", "2500");
        $this->currencyConverter->method('convert')->willReturnArgument(0);
        $this->mathService->method('subtract')->willReturnOnConsecutiveCalls("500", "300", "500");
        $this->mathService->method('multiply')->willReturn("3");
    }
}

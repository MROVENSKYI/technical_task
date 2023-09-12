<?php

declare(strict_types=1);

namespace tests\Service;

use DateTime;
use PHPUnit\Framework\TestCase;
use src\Model\Transaction;
use src\Service\Math;
use src\Strategy\BusinessWithdrawStrategy;

class BusinessWithdrawStrategyTest extends TestCase
{
    private BusinessWithdrawStrategy $strategy;

    protected function setUp(): void
    {
        $mathService = new Math(2);
        $this->strategy = new BusinessWithdrawStrategy($mathService);
    }

    public function testCommissionCurrency(): void
    {
        $transaction = $this->createTransaction();
        $result = $this->strategy->calculate($transaction);

        $this->assertSame('5.00', $result['amount']);
        $this->assertSame('EUR', $result['currency']);
    }

    private function createTransaction(): Transaction
    {
        return new Transaction(
            new DateTime(),
            1,
            'business',
            'withdraw',
            "1000",
            'EUR'
        );
    }
}

<?php

declare(strict_types=1);

namespace tests\Service;

use DateTime;
use PHPUnit\Framework\TestCase;
use src\Model\Transaction;
use src\Service\Math;
use src\Strategy\DepositCommissionStrategy;

class DepositCommissionStrategyTest extends TestCase
{
    private DepositCommissionStrategy $strategy;

    protected function setUp(): void
    {
        $mathService = new Math(2);
        $this->strategy = new DepositCommissionStrategy($mathService);
    }

    public function testCalculateDepositFee()
    {
        $transaction = new Transaction(
            new DateTime(),
            1,
            'private',
            'deposit',
            "1000",
            'EUR'
        );
        $result = $this->strategy->calculate($transaction);
        $this->assertSame('0.30', $result['amount']);
        $this->assertSame('EUR', $result['currency']);
    }
}

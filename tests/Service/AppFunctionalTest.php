<?php

declare(strict_types=1);

namespace tests\Service;

use PHPUnit\Framework\TestCase;

class AppFunctionalTest extends TestCase
{
    private string $expectedOutput = '0.60
3.00
0.00
0.06
1.50
0
0.69
0.30
0.30
3.00
0.00
0.00
8607
';

    public function testScriptOutput(): void
    {
        $ratesJson = '{"base":"EUR","date":"2023-09-12","rates":{"EUR":1,"GBP":0.835342,"JPY":130.869977,"USD":1.129031}}';

        $convertedValue = shell_exec("php script.php input.csv '{$ratesJson}'");

        $this->assertEquals($this->expectedOutput, $convertedValue, "The output of the script does not match the expected amount.");
    }
}
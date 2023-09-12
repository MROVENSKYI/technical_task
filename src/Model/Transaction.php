<?php

declare(strict_types=1);

namespace src\Model;

use DateTime;

class Transaction
{
    private string $userType;
    private string $currency;
    private string $amount;
    private int $user_id;
    private DateTime $date;
    private string $operation_type;

    public function __construct(DateTime $date, int $user_id, string $userType, string $operation_type, string $amount, string $currency)
    {
        $this->date = $date;
        $this->user_id = $user_id;
        $this->userType = $userType;
        $this->operation_type = $operation_type;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getOperationType(): string
    {
        return $this->operation_type;
    }
}

<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class InsufficientBalanceException extends Exception
{
    public function __construct(private readonly int $userId)
    {
        parent::__construct('Insufficient balance.');
    }

    public function report(): void
    {
        Log::error('Insufficient balance exception for user: ' . $this->userId);
    }
}

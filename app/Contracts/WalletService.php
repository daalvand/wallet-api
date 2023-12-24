<?php

namespace App\Contracts;

use App\Exceptions\InsufficientBalanceException;
use App\Models\User;

interface WalletService
{
    public function getBalance(User $user): int;

    /**
     * @throws InsufficientBalanceException
     */
    public function depositMoney(User $user, int $amount): int;
}

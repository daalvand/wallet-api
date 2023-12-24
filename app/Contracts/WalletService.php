<?php

namespace App\Contracts;

use App\Models\User;

interface WalletService
{
    public function getBalance(User $user): int;

    public function depositMoney(User $user, int $amount): int;
}

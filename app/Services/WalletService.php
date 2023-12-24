<?php

namespace App\Services;

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use App\Contracts\WalletService as WalletServiceContract;

class WalletService implements WalletServiceContract
{
    public function getBalance(User $user): int
    {
        return $user->wallet->balance;
    }

    public function depositMoney(User $user, int $amount): int
    {
        return DB::transaction(static function () use ($user, $amount) {
            $wallet = Wallet::lockForUpdate()->find($user->wallet->id);
            $transaction = $user->transactions()->create(['amount' => $amount]);
            $wallet->updateBalance($amount);
            return $transaction->id;
        });
    }
}

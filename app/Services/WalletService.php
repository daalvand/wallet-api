<?php

namespace App\Services;

namespace App\Services;

use App\Models\User;
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
            $transaction = $user->transactions()->create([
                'user_id' => $user->id,
                'amount'  => $amount,
            ]);
            $user->wallet->balance += $amount;
            $user->wallet->save();
            return $transaction->id;
        });
    }
}

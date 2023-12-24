<?php

namespace App\Models;

use App\Exceptions\InsufficientBalanceException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RuntimeException;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['balance'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @throws InsufficientBalanceException
     */
    public function updateBalance(int $amount): self
    {
        if ($this->balance + $amount < 0) {
            throw new InsufficientBalanceException($this->user_id);
        }

        $this->balance += $amount;
        $this->save();
        return $this;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['balance'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function updateBalance(int $amount): self
    {
        $this->balance += $amount;
        $this->save();
        return $this;
    }
}

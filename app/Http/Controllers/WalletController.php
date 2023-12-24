<?php

namespace App\Http\Controllers;

use App\Contracts\WalletService as WalletServiceContract;
use App\Http\Requests\Wallet\DepositMoney;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    private WalletServiceContract $service;

    public function __construct(WalletServiceContract $service)
    {
        $this->service = $service;
    }

    public function showBalance(User $user): JsonResponse
    {
        $balance = $this->service->getBalance($user);
        return response()->json(['balance' => $balance]);
    }

    public function depositMoney(DepositMoney $request, User $user): JsonResponse
    {
        $referenceId = $this->service->depositMoney($user, $request->validated('amount'));
        return response()->json(['reference_id' => $referenceId]);
    }

}

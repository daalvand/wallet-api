<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\Wallet;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

class WalletControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function testShowBalance(): void
    {
        $user = User::factory()->create();
        $response = $this->getJson(route('wallet.balance', ['user' => $user->id]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['balance' => 0]);

        $user->wallet->updateBalance(5000);
        $response = $this->getJson(route('wallet.balance', ['user' => $user->id]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['balance' => 5000]);
    }

    public function testShowBalanceUserNotFound(): void
    {
        $response = $this->getJson(route('wallet.balance', ['user' => 999]));
        $response->assertStatus(404);
    }

    public function testDepositMoney(): void
    {
        $user = User::factory()->create();
        $response = $this->postJson(route('wallet.deposit', ['user' => $user->id]), ['amount' => 1000]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['reference_id' => Transaction::first()->id]);

        $this->assertDatabaseCount(Transaction::class, 1);
        $this->assertDatabaseCount(Wallet::class, 1);

        $this->assertDatabaseHas(Transaction::class, [
            'user_id' => $user->id,
            'amount'  => 1000,
        ]);
        $this->assertDatabaseHas(Wallet::class, [
            'user_id' => $user->id,
            'balance' => 1000,
        ]);

        $response = $this->postJson(route('wallet.deposit', ['user' => $user->id]), ['amount' => -500]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['reference_id' => Transaction::latest('id')->first()->id]);
        $this->assertDatabaseCount(Transaction::class, 2);
        $this->assertDatabaseCount(Wallet::class, 1);


        $this->assertDatabaseHas(Transaction::class, [
            'user_id' => $user->id,
            'amount'  => -500,
        ]);
        $this->assertDatabaseHas(Wallet::class, [
            'user_id' => $user->id,
            'balance' => 500,
        ]);
    }

    public function test_deposit_request_with_insufficient_balance(): void
    {
        $user = User::factory()->create();
        $user->wallet->update(['balance' => 100]);

        $response = $this->postJson(route('wallet.deposit', ['user' => $user]), [
            'amount' => -200,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['amount' => 'The amount field must be greater than or equal to -100.']);
    }

    public function testDepositMoneyValidation(): void
    {
        $user = User::factory()->create();

        //required
        $response = $this->postJson(route('wallet.deposit', ['user' => $user->id]));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['amount'  => 'The amount field is required.']);

        //integer
        $response = $this->postJson(route('wallet.deposit', ['user' => $user->id]), ['amount' => 'not an integer']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['amount' => 'The amount field must be an integer.']);
    }

    public function testDepositMoneyUserNotFound(): void
    {
        $response = $this->postJson(route('wallet.deposit', ['user' => 999]), ['amount' => 1000]);
        $response->assertStatus(404);
    }
}

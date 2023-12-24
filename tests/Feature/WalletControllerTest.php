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
        $user = User::factory()->has(Wallet::factory(['balance' => 5000]))->create();
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
        $user = User::factory()->has(Wallet::factory(['balance' => 5000]))->create();
        $response = $this->postJson(route('wallet.deposit', ['user' => $user->id]), ['amount' => 1000]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'reference_id'
        ]);

        $this->assertDatabaseCount(Transaction::class, 1);
        $this->assertDatabaseCount(Wallet::class, 1);


        $this->assertDatabaseHas(Transaction::class, [
            'user_id' => $user->id,
            'amount'  => 1000,
        ]);
        $this->assertDatabaseHas(Wallet::class, [
            'user_id' => $user->id,
            'balance' => 6000,
        ]);
    }

    public function testDepositMoneyValidation(): void
    {
        $user = User::factory()->has(Wallet::factory(['balance' => 5000]))->create();

        //required
        $response = $this->postJson(route('wallet.deposit', ['user' => $user->id]));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['amount'  => 'The amount field is required.']);

        //integer
        $response = $this->postJson(route('wallet.deposit', ['user' => $user->id]), ['amount' => 'not an integer']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['amount' => 'The amount field must be an integer.']);

        //greater than zero
        $response = $this->postJson(route('wallet.deposit', ['user' => $user->id]), ['amount' => -1000]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['amount' => 'The amount field must be greater than 0.']);
    }

    public function testDepositMoneyUserNotFound(): void
    {
        $response = $this->postJson(route('wallet.deposit', ['user' => 999]), ['amount' => 1000]);
        $response->assertStatus(404);
    }
}

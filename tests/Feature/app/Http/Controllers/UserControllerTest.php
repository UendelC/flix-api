<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testItIsPossibleToStoreANewUser()
    {
        $user_payload = User::factory()->make()->toArray();

        $user_payload['password'] = $this->faker->password();

        $this->postJson('/api/users', $user_payload)
            ->assertStatus(201)
            ->assertJsonFragment(
                [
                    'name' => $user_payload['name'],
                    'email' => $user_payload['email'],
                ]
            );

        $this->assertDatabaseHas(
            (new User)->getTable(),
            [
                'name' => $user_payload['name'],
                'email' => $user_payload['email'],
            ]
        );

        $this->assertTrue(
            Hash::check(
                $user_payload['password'],
                User::where('email', $user_payload['email'])->first()->password
            )
        );
    }

    public function testUserCanLogin()
    {
        $this->artisan(
            'passport:install',
        );

        $password = $this->faker->password();

        $user = User::factory()->create(
            [
                'password' => bcrypt($password),
            ]
        );

        $this->postJson(
            '/api/login',
            [
                'email' => $user->email,
                'password' => $password,
            ]
        )->assertStatus(200)
            ->assertJsonFragment(
                [
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            );
    }
}

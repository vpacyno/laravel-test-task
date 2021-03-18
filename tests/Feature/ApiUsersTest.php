<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\User_details;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiUsersTest extends TestCase
{
    use RefreshDatabase;

    // Checking response and Json structure after one user creation
    public function testUserCreation()
    {
        User_details::factory()->create([

            'user_id' => User::factory()->create()->id
        ]);

        $response = $this->json('GET', 'api/v1/users/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                        'id',
                        'first_name',
                        'last_name',
                        'email',
                        'details' => [
                            'address'
                        ]
                ]
            ])
            ->assertJsonCount(5, 'data');
    }

}

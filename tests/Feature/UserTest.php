<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;

class UserTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function canCreateUser()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);


        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password
        ];

        $response = $this->post('/api/v1/users', $data);
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ]);
    }

    /** @test */
    public function invalidCreateData()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->name,
            'password' => $this->faker->password
        ];

        $response = $this->post('/api/v1/users', $data);
        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'data' => []
                ]);
    }

    /** @test */
    public function canViewUser()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->get('/api/v1/users/' . $user->id);
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ]);

    }

    /** @test */
    public function profileNotFound()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->get('/api/v1/users/' . 1000);
        $response->assertStatus(404)
                ->assertJson([
                    'message' => 'Profile not found.',
                    'data' => []
                ]);
    }

    /** @test */
    public function canUpdateUser()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->patch('/api/v1/users/' . $user->id);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ]);
    }

    /** @test */
    public function canNotUpdateOtherUser()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->name,
            'password' => $this->faker->password
        ];
        $newUser = User::factory()->create($data);
        $data['id'] = $newUser->id;
        $response = $this->patch('/api/v1/users/' . $newUser->id, $data);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Could not update other profile.',
                'data' => []
                ]);
    }
}

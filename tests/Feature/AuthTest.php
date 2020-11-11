<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;



class AuthTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function userCanRegister()
    {
        $password = $this->faker->password;
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email
        ];

        $register = $data;
        $register['password'] = $password;
        $register['password_confirmation'] = $password;
        $response = $this->post('/api/auth/register', $register);
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'accessToken',
                    'data' => [
                        'id',
                        'name',
                        'email'
                    ]
                ]);

    }

    /** @test */
    public function confirmPassword()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password
        ];
        $response = $this->post('/api/auth/register', $data);
        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'data' => []
            ]);
    }

    /** @test */
    public function userCanLogin()
    {
        $email = $this->faker->email;
        $password = $this->faker->password;
        $user = [
            'email' => $email,
            'password' => bcrypt($password)
        ];
        User::factory()->create($user);

        $data = [
            'email' => $email,
            'password' => $password,
            'remember_me' => true
        ];
        $response = $this->post('/api/auth/login', $data);
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'accessToken',
                    'data' => [
                        'id',
                        'name',
                        'email'
                    ]
                ]);

         $this->assertAuthenticated();

    }

    /** @test */
    public function invalidLoginData()
    {
        $response = $this->post('/api/auth/login');
        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'data' => []
            ]);
    }

    /** @test */
    public function unAuthorizedLogin()
    {
        $data = [
            'email' => $this->faker->email,
            'password' => $this->faker->password,
            'remember_me' => true
        ];
        $response = $this->post('/api/auth/login', $data);
        $response
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Login Fail!',
                'data' => []
            ]);
    }
}

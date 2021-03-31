<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user()
    {
        $data = [
            'name' => 'test',
            'email' => 'test@test.com',
            'username' => 'test',
            'password' => '1234',
            'password_confirmation' => '1234'
        ];

        $response = $this->postJson('api/user/register', $data);
        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data']);
    }
}

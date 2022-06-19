<?php

namespace Tests\Feature\Auth;

use Add\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{

    use RefreshDatabase;

    public function test_user_register()
    {

        $response = $this->post('/api/register', ['username' => 'babon123', 'role' => 'admin']);

        $response->assertStatus(200);

    }

    public function test_false_user_register()
    {

        $response = $this->post('/api/register', ['username' => 'babo3', 'role' => 'admin']);

        $response->assertStatus(400);

    }

    public function test_user_login()
    {

        $register = $this->post('/api/register', ['username' => 'babon123', 'role' => 'admin']);

        $register->assertStatus(200);

        $response = $this->post('/api/login', ['username' => 'babon123', 'password' => $register['password']]);

        $response->assertStatus(200);
    }

    public function test_false_user_login()
    {


        $response = $this->post('/api/login', ['username' => 'babon123x', 'password' => 'secret']);

        $response->assertStatus(401);
    }

    public function test_user_validate_token()
    {

        $register = $this->post('/api/register', ['username' => 'babon123', 'role' => 'admin']);

        $register->assertStatus(200);

        $login = $this->post('/api/login', ['username' => 'babon123', 'password' => $register['password']]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $login['token'],
        ])->get('/api/validate_token');

        $response->assertStatus(200);
    }

    public function test_false_user_validate_token()
    {

        $response = $this->get('/api/validate_token');

        $response->assertStatus(500);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
	use DatabaseTransactions;

    /**
     * Create User test
     *
     * @return void
     */
    public function testCreateUser()
    {
        $user = [
        	'full_name' => 'Praditha Hidayat',
        	'email' => 'testing@mail.com',
			'password' => 'secret',
			'password_confirmation' => 'secret',
			'birth_date' => '1988/11/24'
        ];

        $api = $this->call('POST', 'api/v1/users', $user);

        $api->assertStatus(200)
        	->assertJson([
        		"status" => "OK",
    			"message" => "User successfully registered",
        	]);

        $this->assertDatabaseHas('users', [
        	'full_name' => 'Praditha Hidayat',
        	'email' => 'testing@mail.com',
        	'birth_date' => '1988-11-24'
        ]);
    }

    /**
     * Get User Detail Test
     * @return void
     */
    public function testGetUserDetail() {
    	$user = factory(\App\Models\User::class)->create();
    	
        $api = $this->call('GET', 'api/v1/users/' . $user->id);

        $api->assertStatus(200)
        	->assertJson([
        		"status" => "OK",
    			"message" => "User has been found",
        	]);
    }

    /**
     * Update User Detail Test
     * @return void
     */
    public function testUpdateUser() {
    	$userData = [
        	'full_name' => 'Praditha Hidayat',
        	'email' => 'testing@mail.co.id',
			'password' => 'secret',
			'password_confirmation' => 'secret',
			'birth_date' => '1988/11/24'
        ];

    	$user = factory(\App\Models\User::class)->create();
    	
        $api = $this->call('POST', 'api/v1/users/' . $user->id, $userData);

        $api->assertStatus(200)
        	->assertJson([
        		"status" => "OK",
    			"message" => "User successfully update",
        	]);

        $this->assertDatabaseHas('users', [
        	'full_name' => 'Praditha Hidayat',
        	'email' => 'testing@mail.co.id',
        	'birth_date' => '1988-11-24'
        ]);
    }

    /** 
     * Delete User Test
     * @return void
     */
    public function testDeleteUser() {
    	$user = factory(\App\Models\User::class)->create();
    	
        $api = $this->call('DELETE', 'api/v1/users/' . $user->id);

        $api->assertStatus(200)
        	->assertJson([
        		"status" => "OK",
    			"message" => "User successfully delete",
        	]);

        $this->assertDatabaseMissing('users', [
        	'full_name' => $user->full_name,
        	'email' => $user->email
        ]);
    }

    /**
     * Authentication User Test
     * @return void
     */
    public function testAuthentication() {
    	$user = factory(\App\Models\User::class)->create();

    	$api = $this->call('POST', 'api/v1/authenticate', [
    		'email' => $user->email,
    		'password' => 'secret'
    	]);

        $api->assertStatus(200)
        	->assertJson([
        		"status" => "OK",
    			"message" => "Login Successfull",
        	]);
    }
}

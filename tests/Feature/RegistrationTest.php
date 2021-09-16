<?php

    namespace Tests\Feature;

    use Tests\TestCase;

    class RegistrationTest extends TestCase
    {

        public function test_user_sign_up_successfully()
        {
            $response = $this->postJson('/register',
                [
                    'name' => 'Rachid Rafia', 'email' => 'test@test.com', 'password' => 'testtest',
                    'password_confirmation' => 'testtest',
                ]);
            $response->assertStatus(201);
        }

        public function test_user_sign_up_failed_with_validation_errors()
        {
            $response = $this->postJson('/register',
                [
                    'name' => 'Rachid Rafia', 'email' => 'test', 'password' => 'testtestsss',
                    'password_confirmation' => 'testtest',
                ]);
            $response->assertStatus(422);
        }
    }

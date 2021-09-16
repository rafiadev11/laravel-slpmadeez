<?php

    namespace Tests\Feature;

    use App\Models\User;
    use Tests\TestCase;

    class LoginTest extends TestCase
    {
        public function test_user_log_in_successfully()
        {
            $user = User::factory()->create();
            $response = $this->postJson('/login',
                [
                    'email' => $user->email, 'password' => 'testtest',
                ]);
            $response->assertStatus(200);
        }

        public function test_user_log_in_failed()
        {
            $user = User::factory()->create();
            $response = $this->postJson('/login',
                [
                    'email' => $user->email, 'password' => 'fdsfdsfdsfsd',
                ]);
            $response->assertStatus(422);
        }
    }

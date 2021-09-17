<?php

    namespace Tests\Feature;

    use App\Models\School;
    use App\Models\User;
    use Tests\TestCase;

    class SchoolTest extends TestCase
    {
        protected function setUp(): void
        {
            parent::setUp();
            $user = User::factory()->create();
            $this->actingAs($user);
        }


        public function test_get_all_schools()
        {
            School::factory()->count(2)->create();
            $schools = $this->getJson('/api/schools');
            $schools->assertOk()->assertJsonCount(2);

        }

        public function test_get_a_single_school()
        {
            $school = School::factory()->create();
            $schools = $this->getJson('/api/schools/'.$school->id);
            $schools->assertOk()->assertJson(['name' => $school->name]);
        }
    }

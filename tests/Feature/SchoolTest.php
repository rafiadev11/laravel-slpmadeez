<?php

    namespace Tests\Feature;

    use App\Models\School;
    use App\Models\User;
    use Illuminate\Foundation\Testing\WithFaker;
    use Tests\TestCase;

    class SchoolTest extends TestCase
    {
        use WithFaker;

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

        public function test_create_a_school()
        {
            $formData = School::factory()->make()->toArray();
            $this->postJson('/api/schools', $formData)->assertCreated();
            $this->assertDatabaseHas('schools', ['name' => $formData['name']]);
        }

        public function test_create_a_school_validation_failed(){
            $formData = ['name' => null];
            $this->postJson('/api/schools', $formData)->assertStatus(422);
        }

        public function test_update_a_school()
        {
            $school = School::factory()->create();
            $formData = ['name' => $this->faker->name];
            $this->patchJson('/api/schools/'.$school->id, $formData)->assertOk();
            $this->assertDatabaseHas('schools', ['name' => $formData['name']]);
        }

        public function test_update_a_school_validation_failed(){
            $school = School::factory()->create();
            $formData = ['name' => null];
            $this->patchJson('/api/schools/'.$school->id, $formData)->assertStatus(422);
        }

        public function test_delete_a_school(){
            $school = School::factory()->create();
            $this->deleteJson('/api/schools/'.$school->id)->assertOk();
            $this->assertSoftDeleted('schools',['name'=> $school->name]);
        }
    }

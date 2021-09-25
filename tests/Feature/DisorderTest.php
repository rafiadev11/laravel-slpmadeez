<?php

    namespace Tests\Feature;

    use App\Models\Disorder;
    use App\Models\User;
    use Illuminate\Foundation\Testing\WithFaker;
    use Tests\TestCase;

    class DisorderTest extends TestCase
    {
        use WithFaker;

        protected function setUp(): void
        {
            parent::setUp();
            $user = User::factory()->create();
            $this->actingAs($user);
        }

        public function test_get_all_disorders()
        {
            Disorder::factory()->count(4)->create();
            $schools = $this->getJson('/api/disorders');
            $schools->assertOk()->assertJsonCount(4);
        }

        public function test_get_a_single_disorder()
        {
            $disorder = Disorder::factory()->create();
            $schools = $this->getJson('/api/disorders/'.$disorder->id);
            $schools->assertOk()->assertJson(['name' => $disorder->name]);
        }

        public function test_create_a_disorder()
        {
            $formData = Disorder::factory()->make()->toArray();
            $this->postJson('/api/disorders', $formData)->assertCreated();
            $this->assertDatabaseHas('disorders', ['name' => $formData['name']]);
        }

        public function test_create_a_disorder_validation_failed(){
            $formData = ['name' => null];
            $this->postJson('/api/disorders', $formData)->assertStatus(422);
        }

        public function test_update_a_school()
        {
            $disorder = Disorder::factory()->create();
            $formData = ['name' => $this->faker->word];
            $this->patchJson('/api/disorders/'.$disorder->id, $formData)->assertOk();
            $this->assertDatabaseHas('disorders', ['name' => $formData['name']]);
        }

        public function test_update_a_school_validation_failed(){
            $disorder = Disorder::factory()->create();
            $formData = ['name' => null];
            $this->patchJson('/api/disorders/'.$disorder->id, $formData)->assertStatus(422);
        }

        public function test_delete_a_school(){
            $disorder = Disorder::factory()->create();
            $this->deleteJson('/api/disorders/'.$disorder->id)->assertOk();
            $this->assertSoftDeleted('disorders',['name'=> $disorder->name]);
        }
    }

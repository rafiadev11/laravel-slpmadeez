<?php

    namespace Tests\Feature;

    use App\Models\SchoolYear;
    use App\Models\User;
    use Illuminate\Foundation\Testing\WithFaker;
    use Tests\TestCase;

    class SchoolYearsTest extends TestCase
    {
        use WithFaker;

        protected function setUp(): void
        {
            parent::setUp();
            $user = User::factory()->create();
            $this->actingAs($user);
        }


        public function test_get_school_years_by_school()
        {
            $schoolYear = SchoolYear::factory()->create();
            $schoolYears = $this->getJson('/api/school-years/'.$schoolYear->id);
            $schoolYears->assertOk()->assertJsonCount(1);
        }

        public function test_create_a_school_year()
        {
            $formData = SchoolYear::factory()->make()->toArray();
            $this->postJson('/api/school-years', $formData)->assertCreated();
            $this->assertDatabaseHas('school_years', ['start' => $formData['start']]);
        }

        public function test_create_a_school_validation_failed(){
            $formData = ['start' => null];
            $this->postJson('/api/school-years', $formData)->assertStatus(422);
        }

        public function test_update_a_school()
        {
            $schoolYear = SchoolYear::factory()->create();
            $formData = SchoolYear::factory()->make()->toArray();
            $this->patchJson('/api/school-years/'.$schoolYear->id, $formData)->assertOk();
            $this->assertDatabaseHas('school_years', ['start' => $formData['start']]);
        }

        public function test_update_a_school_validation_failed(){
            $schoolYear = SchoolYear::factory()->create();
            $formData = ['start' => null];
            $this->patchJson('/api/school-years/'.$schoolYear->id, $formData)->assertStatus(422);
        }

        public function test_delete_a_school(){
            $schoolYear = SchoolYear::factory()->create();
            $this->deleteJson('/api/school-years/'.$schoolYear->id)->assertOk();
            $this->assertDatabaseMissing('school_years',['school_id'=> $schoolYear->id,'start'=> $schoolYear->start]);
        }
    }

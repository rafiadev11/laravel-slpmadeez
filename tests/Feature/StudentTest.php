<?php

    namespace Tests\Feature;

    use App\Models\Disorder;
    use App\Models\Objective;
    use App\Models\Schedule;
    use App\Models\SchoolYear;
    use App\Models\Student;
    use App\Models\User;
    use Illuminate\Foundation\Testing\WithFaker;
    use Tests\TestCase;

    class StudentTest extends TestCase
    {
        use WithFaker;

        protected function setUp(): void
        {
            parent::setUp();
            $user = User::factory()->create();
            $this->actingAs($user);
        }

        public function test_add_new_student()
        {
            $student = Student::factory()->create();
            $disorder = Disorder::factory()->count(2)->create();
            $schoolYear = SchoolYear::factory()->create();
            $schedule = Schedule::factory()->count(2)->create();
            $objective = Objective::factory()->count(5)->create();
            $formData = [
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'grade' => $student->grade,
                'school_year_id' => $schoolYear->id,
                'disorders' => [
                    0 => [
                        'id' => $disorder[0]->id,
                        'annual_minutes' => 500,
                        'schedule' => [
                            0 => [
                                'day' => $schedule[0]->day,
                                'start_time' => $schedule[0]->start_time,
                                'end_time' => $schedule[0]->end_time,
                            ],
                            1 => [
                                'day' => $schedule[1]->day,
                                'start_time' => $schedule[1]->start_time,
                                'end_time' => $schedule[1]->end_time,
                            ],
                        ],
                    ],
                    1 => [
                        'id' => $disorder[1]->id,
                        'annual_minutes' => 600,
                        'schedule' => [
                            0 => [
                                'day' => $schedule[0]->day,
                                'start_time' => $schedule[0]->start_time,
                                'end_time' => $schedule[0]->end_time,
                            ],
                            1 => [
                                'day' => $schedule[1]->day,
                                'start_time' => $schedule[1]->start_time,
                                'end_time' => $schedule[1]->end_time,
                            ],
                        ],
                    ],
                ],
                'objectives' => [
                    $objective[0]->goal,
                    $objective[1]->goal,
                    $objective[2]->goal,
                    $objective[3]->goal,
                    $objective[4]->goal,
                ],
            ];
            $this->postJson('/api/students', $formData)->assertCreated();

        }

        // create a student
        // update a student
        // delete a student
        // get students
        // export student to new school year
    }

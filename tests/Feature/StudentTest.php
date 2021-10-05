<?php

    namespace Tests\Feature;

    use App\Models\Disorder;
    use App\Models\Goal;
    use App\Models\Objective;
    use App\Models\Schedule;
    use App\Models\SchoolYear;
    use App\Models\Student;
    use App\Models\User;
    use Illuminate\Foundation\Testing\WithFaker;
    use Illuminate\Http\Request;
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

//        public function test_get_students_by_school_year()
//        {
//            $student = $this->test_set_student_data();
//            $this->getJson('/api/students/'.$student['school_year_id'])
//                ->assertOk()
//                ->assertJsonCount(2);
//        }

        public function test_get_students_by_school_year_and_disorder()
        {
            $student = $this->test_set_student_data();
            $st = $this->getJson('/api/students/'.$student['school_year_id'].'/'.$student['disorder_id'])
                ->assertOk()
                ->assertJsonCount(1);
        }

        public function test_add_new_student()
        {
            $student = $this->test_set_student_data();
            $goal = Goal::where('student_id', $student['student_id'])
                ->where('school_year_id', $student['school_year_id'])
                ->where('disorder_id', $student['disorder_id'])
                ->first();
            $this->assertDatabaseHas('students', ['first_name' => $student['first_name']]);
            $this->assertDatabaseHas('goals', ['id' => $goal->id]);
            $this->assertDatabaseHas('schedules', ['goal_id' => $goal->id]);
            $this->assertDatabaseHas('objectives', ['goal_id' => $goal->id]);
        }

        public function test_update_student()
        {
            $student = Student::factory()->create();
            $formData = ['first_name' => "Rachid", "last_name" => "Rafia"];
            $this->patchJson('/api/students/'.$student->id, $formData)->assertOk();
            $this->assertDatabaseHas('students', ['first_name' => "Rachid"]);
        }

        public function test_update_student_goal()
        {
            $student = $this->test_set_student_data();
            $goal = Goal::where('student_id', $student['student_id'])
                ->where('school_year_id', $student['school_year_id'])
                ->where('disorder_id', $student['disorder_id'])
                ->first();
            $formData = [
                'school_year_id' => $student['school_year_id'],
                'disorder_id' => $student['disorder_id'],
                'student_id' => $student['student_id'],
                'annual_minutes' => 9999,
                'schedule' => [
                    0 => [
                        'id' => 1,
                        'day' => 'Monday',
                        'start_time' => '9:50 am',
                        'end_time' => '10:00 am',
                    ],
                    1 => [
                        'id' => 2,
                        'day' => 'Wednesday',
                        'start_time' => '10:45 am',
                        'end_time' => '10:55 am',
                    ],
                    2 => [
                        'id' => 3,
                        'day' => 'Thursday',
                        'start_time' => '11:45 am',
                        'end_time' => '11:55 am',
                    ],
                ],
                'objectives' => [
                    'test 1',
                    'test 2',
                ],
            ];
            $this->patchJson('/api/students/goal/'.$goal->id, $formData)->assertOk();
            $this->assertDatabaseHas('goals', ['disorder_id' => $student['disorder_id']]);
            $this->assertDatabaseHas('schedules', ['day' => 'Monday', 'start_time' => '9:50 am']);
            $this->assertDatabaseHas('objectives', ['goal' => 'test 1']);
        }

        public function test_deactivate_a_goal()
        {
            $student = $this->test_set_student_data();
            $goal = Goal::where('student_id', $student['student_id'])
                ->where('school_year_id', $student['school_year_id'])
                ->where('disorder_id', $student['disorder_id'])
                ->first();
            $formData = [
                'goal_id' => $goal->id,
                'school_year_id' => $student['school_year_id'],
                'disorder_id' => $student['disorder_id'],
                'student_id' => $student['student_id'],
            ];
            $this->patchJson('/api/students/goal/'.$goal->id.'/deactivate', $formData)->assertOk();
            $this->assertDatabaseHas('goals', ['id' => $goal->id, 'active' => 0]);
        }

        private function test_set_student_data(): array
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
                    ],
                    1 => [
                        'id' => $disorder[1]->id,
                    ],
                ],
                'sessions' => [
                    0 => [
                        'disorder_id' => $disorder[0]->id,
                        'duration' => "5",
                        'perMonth' => "6",
                        'schedule' => [
                            0 => [
                                'day' => 'Monday',
                                'checked' => false,
                                'time' => [
                                    'startTime' => null,
                                    'endTime' => null,
                                ],
                            ],
                            1 => [
                                'day' => 'Tuesday',
                                'checked' => true,
                                'time' => [
                                    'startTime' => "10:10",
                                    'endTime' => "10:15",
                                ],
                            ],
                        ],
                    ],
                ],
                'objectives' => [
                    0 => [
                        'disorder_id' => $disorder[0]->id,
                        'values' => [
                            0 => [
                                'name' => 'test 1',
                            ],
                            1 => [
                                'name' => 'test 2',
                            ],
                            2 => [
                                'name' => 'test 3',
                            ],
                        ],
                    ],
                ],
            ];
            $student = $this->postJson('/api/students', $formData);
            $student->assertCreated();
            return [
                'student_id' => $student['id'],
                'school_year_id' => $formData['school_year_id'],
                'disorder_id' => $formData['disorders'][0]['id'],
                'first_name' => $formData['first_name'],
            ];
        }

        // export student to new school year
    }

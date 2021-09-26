<?php

    namespace Database\Factories;

    use App\Models\Disorder;
    use App\Models\Goal;
    use App\Models\SchoolYear;
    use App\Models\Student;
    use Illuminate\Database\Eloquent\Factories\Factory;

    class GoalFactory extends Factory
    {
        /**
         * The name of the factory's corresponding model.
         *
         * @var string
         */
        protected $model = Goal::class;


        /**
         * Define the model's default state.
         *
         * @return array
         */
        public function definition()
        {
            return [
                'school_year_id' => function () { return SchoolYear::factory()->create()->id; },
                'student_id' => function () { return Student::factory()->create()->id; },
                'disorder_id' => function () { return Disorder::factory()->create()->id; },
                'annual_minutes' => $this->faker->numberBetween(100, 1000),
                'active' => true,
            ];
        }
    }

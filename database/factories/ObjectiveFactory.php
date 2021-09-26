<?php

    namespace Database\Factories;

    use App\Models\Disorder;
    use App\Models\Goal;
    use App\Models\Objective;
    use App\Models\SchoolYear;
    use App\Models\Student;
    use Illuminate\Database\Eloquent\Factories\Factory;

    class ObjectiveFactory extends Factory
    {
        /**
         * The name of the factory's corresponding model.
         *
         * @var string
         */
        protected $model = Objective::class;


        /**
         * Define the model's default state.
         *
         * @return array
         */
        public function definition()
        {
            return [
                'goal_id' => function () { return Goal::factory()->create()->id; },
                'goal' => $this->faker->paragraph(),
                'notes' => $this->faker->sentence(),
            ];
        }
    }

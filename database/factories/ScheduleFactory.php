<?php

    namespace Database\Factories;

    use App\Models\Goal;
    use App\Models\Schedule;
    use Illuminate\Database\Eloquent\Factories\Factory;

    class ScheduleFactory extends Factory
    {
        /**
         * The name of the factory's corresponding model.
         *
         * @var string
         */
        protected $model = Schedule::class;


        /**
         * Define the model's default state.
         *
         * @return array
         */
        public function definition()
        {
            return [
                'goal_id' => function () { return Goal::factory()->create()->id; },
                'day' => $this->faker->dayOfWeek,
                'start_time' => $this->faker->time('g:i a'),
                'end_time' => $this->faker->time('g:i a'),
            ];
        }
    }

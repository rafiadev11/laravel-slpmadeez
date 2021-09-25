<?php

    namespace Database\Factories;

    use App\Models\School;
    use App\Models\SchoolYear;
    use App\Models\User;
    use Illuminate\Database\Eloquent\Factories\Factory;

    class SchoolYearFactory extends Factory
    {
        /**
         * The name of the factory's corresponding model.
         *
         * @var string
         */
        protected $model = SchoolYear::class;


        /**
         * Define the model's default state.
         *
         * @return array
         */
        public function definition()
        {
            return [
                'school_id' => function(){return School::factory()->create()->id;},
                'start' => $this->faker->date(),
                'end' => $this->faker->date(),
            ];
        }
    }

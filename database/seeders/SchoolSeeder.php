<?php

    namespace Database\Seeders;

    use App\Models\School;
    use App\Models\SchoolYear;
    use App\Models\User;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\Auth;

    class SchoolSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            $user = User::where('email', 'rafiadev@gmail.com')->first();
            Auth::login($user);
            School::factory()
                ->count(4)
                ->create(['user_id' => $user->id])
                ->each(function ($school) {
                    SchoolYear::factory()->count(2)->create(['school_id' => $school->id]);
                });
        }
    }

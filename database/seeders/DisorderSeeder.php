<?php

    namespace Database\Seeders;


    use App\Models\Disorder;
    use App\Models\User;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\Auth;

    class DisorderSeeder extends Seeder
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
            Disorder::factory()
                ->count(4)
                ->create(['user_id' => $user->id]);
        }
    }

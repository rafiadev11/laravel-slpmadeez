<?php

    namespace Database\Seeders;

    use App\Models\School;
    use App\Models\SchoolYear;
    use App\Models\User;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\Auth;

    class UserSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            User::factory()
                ->count(2)
                ->create();
        }
    }

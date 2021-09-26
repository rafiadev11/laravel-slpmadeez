<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateSchedulesTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('goal_id')->constrained('goals')->onDelete('cascade');
                $table->string('day');
                $table->string('start_time');
                $table->string('end_time');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('schedules');
        }
    }

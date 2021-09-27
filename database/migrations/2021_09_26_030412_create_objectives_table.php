<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateObjectivesTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('objectives', function (Blueprint $table) {
                $table->id();
                $table->foreignId('goal_id')->constrained('goals')->onDelete('cascade');
                $table->text('goal');
                $table->text('notes')->nullable();
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
            Schema::dropIfExists('objectives');
        }
    }

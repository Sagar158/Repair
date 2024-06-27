<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('user_id');
            $table->string('job_type');
            $table->string('system_type');
            $table->string('warranty_job');
            $table->string('est_labour_cost')->nullable();
            $table->string('est_parts_cost')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('Queued');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->json('items')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('name');
            // $table->integer('menu_order');
            // $table->timestamp('planned_start_date')->nullable();
            // $table->timestamp('planned_end_date')->nullable();
            // $table->timestamp('actual_start_date')->nullable();
            // $table->timestamp('actual_end_date')->nullable();
            $table->boolean('active')->default(1);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('projects');
    }
}

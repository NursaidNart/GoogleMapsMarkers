<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_maps_markers', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable()->default(NULL);
            $table->string('lat')->nullable()->default(NULL);
            $table->string('long')->nullable()->default(NULL);
            $table->unsignedBigInteger('user_id')->nullable()->default(NULL);
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
        Schema::dropIfExists('google_maps_markers');
    }
};

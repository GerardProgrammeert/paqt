<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resident_id');
            $table->dateTime('pickup_moment');
            $table->string('from');
            $table->string('to');
            $table->unsignedFloat('distance');
            $table->boolean('is_driven')->default(false);
            $table->timestamps();

            $table->foreign('resident_id')
                ->references('id')
                ->on('residents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};

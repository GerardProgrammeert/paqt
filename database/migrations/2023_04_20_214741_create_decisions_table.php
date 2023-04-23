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
        Schema::create('decisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resident_id');
            $table->float('balance', 10, 2);
            $table->date('prolongation_date');
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('decisions');
    }
};

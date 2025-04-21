<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->string('name');
            
            // Use a more portable data type for boolean values across different DB systems
            if (config('database.default') === 'pgsql') {
                // For PostgreSQL, use a native boolean type but make it accept integers too
                $table->boolean('personal_company')->default(false);
            } else {
                // For SQLite and others, use a boolean which SQLite stores as integer
                $table->boolean('personal_company');
            }
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

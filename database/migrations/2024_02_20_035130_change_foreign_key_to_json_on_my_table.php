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
        Schema::table('mst_chapters', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['class_id']);
            
            // Drop the class_id column
            $table->dropColumn('class_id');
            
            // Add a new JSON column for storing class-related data
            $table->json('class_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mst_chapters', function (Blueprint $table) {
             // Add the class_id column back
            $table->unsignedBigInteger('class_id')->nullable();
            
            // Optionally, you can add the foreign key constraint back if needed
            // $table->foreign('class_id')->references('id')->on('mst_class');

            // Remove the JSON column
            $table->dropColumn('class_data');
        });
    }
};

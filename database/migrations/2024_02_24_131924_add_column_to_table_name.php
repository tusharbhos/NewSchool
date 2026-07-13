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
            $table->integer('visibility')->default(0)->comment('1:All Classes, 0:Class Specific');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mst_chapters', function (Blueprint $table) {
            //
        });
    }
};

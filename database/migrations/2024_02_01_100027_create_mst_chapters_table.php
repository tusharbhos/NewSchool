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
        Schema::create('mst_chapters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->date('release_date');
            $table->string('chapter_image')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->string('slug')->nullable();
            $table->string('asset_path')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->integer('status')->default(1)->comment('1:Active, 0:Inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_chapters');
    }
};

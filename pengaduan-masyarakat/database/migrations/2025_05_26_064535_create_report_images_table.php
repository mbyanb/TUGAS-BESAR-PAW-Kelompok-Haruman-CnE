<?php
// database/migrations/xxxx_create_report_images_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('report_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->string('original_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_images');
    }
};
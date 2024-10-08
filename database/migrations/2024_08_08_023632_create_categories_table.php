<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->dateTime("created_at")->nullable()->useCurrent();
            $table->foreignUuid("created_by")->nullable();
            $table->dateTime("updated_at")->nullable()->useCurrent();
            $table->foreignUuid("updated_by")->nullable();
            $table->dateTime("deleted_at")->nullable();
            $table->foreignUuid("deleted_by")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('categories');
    }
};

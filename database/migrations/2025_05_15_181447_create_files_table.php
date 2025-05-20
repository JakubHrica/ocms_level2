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
        Schema::create('files', function (Blueprint $table) {
            $table->id();

            $table->string('filename');
            $table->string('filepath')->nullable(); // alebo použiješ `url` ak ukladáš do cloudu
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('attachment_id'); // polymorphic
            $table->string('attachment_type'); // polymorphic
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->index(['attachment_id', 'attachment_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
};

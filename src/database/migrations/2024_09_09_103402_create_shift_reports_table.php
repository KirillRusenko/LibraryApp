<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::connection('mongodb')->create('shift_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shift_id');
            $table->date('shift_date');
            $table->json('employee_ids');
            $table->unsignedBigInteger('shift_author');
            $table->integer('total_books_count')->nullable();
            $table->integer('borrowed_books_count')->nullable();
            $table->integer('returned_books_count')->nullable();
            $table->integer('books_on_repair_count')->nullable();
            $table->integer('available_books_count')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('shift_reports');
    }
};

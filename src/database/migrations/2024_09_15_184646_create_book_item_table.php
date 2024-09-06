<?php

use App\Models\BookItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('book_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained();
            $table->string('isbn')->unique();
            $table->year('publication_year');
            $table->foreignId('publisher_id')->constrained();
            $table->timestamp('last_returned_at')->nullable();
            $table->timestamp('last_borrowed_at')->nullable();
            $table->boolean('is_borrowed')->default(false);
            $table->string('condition')->default(BookItem::CONDITION_GOOD);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_items');
    }
};

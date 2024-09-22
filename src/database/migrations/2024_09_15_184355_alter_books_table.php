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
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('isbn')->default(false);
            $table->dropColumn('publication_year')->default(false);
            $table->dropForeign('books_publisher_id_foreign');
            $table->dropColumn('publisher_id')->default(false);
            $table->dropColumn('is_borrowed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

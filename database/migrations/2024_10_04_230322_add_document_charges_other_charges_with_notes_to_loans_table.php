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
        Schema::table('loans', function (Blueprint $table) {
            //
            $table->decimal('document_charges', 10, 2)->nullable(); 
            $table->decimal('other_charges', 10, 2)->nullable(); 
            $table->text('notes_for_other_charges')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            //
            $table->dropColumn('document_charges');
            $table->dropColumn('other_charges');
            $table->dropColumn('notes_for_other_charges');
        });
    }
};

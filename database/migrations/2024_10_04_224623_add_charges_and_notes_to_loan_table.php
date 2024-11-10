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
            $table->decimal('document_charges', 15, 2)->nullable()->after('applied_amount');
            $table->decimal('other_charges', 15, 2)->nullable()->after('document_charges');
            $table->text('notes_for_other_charges')->nullable()->after('other_charges');

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

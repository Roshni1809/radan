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
            
            $table->unsignedBigInteger('branch_account_id')->nullable();
    
            // Set up foreign key constraints
            $table->foreign('branch_account_id')->references('id')->on('branch_accounts')->onDelete('set null');
        });
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['branch_account_id']);
            $table->dropColumn(['branch_account_id']);
        });
    }
};

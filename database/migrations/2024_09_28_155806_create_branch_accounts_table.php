<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('branch_accounts', function (Blueprint $table) {
            $table->id();  
            $table->string('account_name');  
            $table->string('account_type'); 
            $table->unsignedBigInteger('branch_id'); 
            $table->decimal('amount', 15, 2);  
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('branch_accounts');
    }
}
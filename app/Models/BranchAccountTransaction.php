<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchAccountTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id', 'account_id', 'user_id', 'amount', 'transaction_type'];

    // Define relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function account()
    {
        return $this->belongsTo(BranchAccount::class, 'account_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
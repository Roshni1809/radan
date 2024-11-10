<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchAccount extends Model
{
    use HasFactory;
    
    protected $table = 'branch_accounts';

    protected $fillable = ['account_name', 'account_type', 'branch_id', 'amount'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    // In BranchAccount.php model
public function branchAccountTransactions()
{
    return $this->hasMany(BranchAccountTransaction::class, 'account_id');
}

}

<?php

namespace App\Http\Controllers;

use App\Models\BranchAccountTransaction;
use App\Models\Branch;
use App\Models\BranchAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchAccountTransactionController extends Controller {


    public function index()
    {
        $branchTransactions = BranchAccountTransaction::all()->sortByDesc("id");
        return view('backend.branch.branch_transactions.list', compact('branchTransactions'));
    }


    public function create()
    {
        // Fetch all branches to display in the dropdown
        $branches = BranchAccountTransaction::all();
        return view('backend.branch.branch_transactions.create', compact('branches'));
    }


    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'branch' => 'required|exists:branches,id',
            'branch_account' => 'required|exists:branch_accounts,id',
            'amount' => 'required|numeric',
            'transaction_type' => 'required|in:debit,credit'
        ]);

        // Create a new transaction record
        $branchAccountTransaction = BranchAccountTransaction::create([
            'branch_id' => $validatedData['branch'],
            'account_id' => $validatedData['branch_account'],
            'user_id' => Auth::id(), 
            'amount' => $validatedData['amount'],
            'transaction_type' => $validatedData['transaction_type']
        ]);

        $account = BranchAccount::find($validatedData['branch_account']);

        if ($validatedData['transaction_type'] == 'credit') {
            $account->amount += $validatedData['amount'];
        } elseif ($validatedData['transaction_type'] == 'debit') {
            $account->amount -= $validatedData['amount'];
        }

        $account->save();
        
        $savedData = [];
        $savedData['branch_name'] = Branch::find($validatedData['branch'])->name;
        $savedData['user_name'] = Auth::user()->name;
        $savedData['account_name'] = $account->account_name;
        $savedData['transaction_date'] = \Carbon\Carbon::parse($branchAccountTransaction->timestamp)->format('d-m-Y H:i');
        $savedData['transaction_type'] = $branchAccountTransaction->transaction_type;
        $savedData['amount'] = $branchAccountTransaction->amount;

        if (!$request->ajax()) {
            return redirect()->route('branch_account_transactions.index')->with('success', 'Transaction created successfully.');
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Successfully'), 'data' => $savedData, 'table' => '#branche_transaction_table']);
        }

    }

    public function show($id)
    {
        // Fetch a single transaction
        $transaction = BranchAccountTransaction::with(['branch', 'account', 'user'])->findOrFail($id);
        return view('backend.branch.branch_transactions.view', compact('transaction'));
    }

    public function edit($id)
    {
        // Fetch transaction and related data for editing
        $transaction = BranchAccountTransaction::findOrFail($id);
        $branches = Branch::all();
        return view('backend.branch.branch_transactions.edit', compact('transaction', 'branches'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'account_id' => 'required|exists:branch_accounts,id',
            'amount' => 'required|numeric',
            
        ]);

        // Update the transaction record
        $transaction = BranchAccountTransaction::findOrFail($id);
        $branchAccountTransaction = $transaction->update([
            'branch_id' => $validatedData['branch_id'],
            'account_id' => $validatedData['account_id'],
            'amount' => $validatedData['amount'],
            'transaction_type' => $validatedData['transaction_type'],
        ]);

        $account = BranchAccount::find($validatedData['account_id']);

        if ($validatedData['transaction_type'] == 'credit') {
            $account->balance += $validatedData['amount'];
        } elseif ($validatedData['transaction_type'] == 'debit') {
            $account->balance -= $validatedData['amount'];
        }

        $account->save();

        $savedData = [];
        $savedData['branch_name'] = Branch::find($validatedData['branch'])->name;
        $savedData['user_name'] = Auth::user()->name;
        $savedData['account_name'] = $account->account_name;
        $savedData['transaction_date'] = \Carbon\Carbon::parse($branchAccountTransaction->timestamp)->format('d-m-Y H:i');
        $savedData['transaction_type'] = $branchAccountTransaction->transaction_type;
        $savedData['amount'] = $branchAccountTransaction->amount;

        if (!$request->ajax()) {
            return redirect()->route('branch_account_transactions.index')->with('success', 'Transaction created successfully.');
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Successfully'), 'data' => $savedData, 'table' => '#branche_transaction_table']);
        }
    }

    public function destroy($id)
    {
        // Find and delete the transaction
        $transaction = BranchAccountTransaction::findOrFail($id);
       
        $account = BranchAccount::findOrFail($transaction->account_id);

        if ($transaction->transaction_type == 'debit') {
            $account->amount += $transaction->amount;
        } elseif ($transaction->transaction_type == 'credit') {
            $account->amount -= $transaction->amount;
        }

        $account->save();

        $transaction->delete();
        return redirect()->route('branch_account_transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BranchAccount;
use App\Models\BranchAccountTransaction;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BranchAccountController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        date_default_timezone_set(get_option('timezone', 'Asia/Dhaka'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $branchAccounts = BranchAccount::all()->sortByDesc("id");

         return view('backend.branch.branch_accounts.list', compact('branchAccounts'));
        
    }
    

    public function create()
    {
        $branches = Branch::all();

        return view('backend.branch.branch_accounts.create');
    }

     public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|string|max:255',
            'branch' => 'required|exists:branches,id', 
            'balance' => 'required|numeric',
        ]);

        if ($validatedData->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validatedData->errors()->all()]);
            } else {
                return redirect()->route('branch_accounts.create')
                    ->withErrors($validatedData)
                    ->withInput();
            }
        }
       
        $data = $validatedData->validated();

        $branchAccount                = new BranchAccount();
        $branchAccount->account_name = $data['account_name'];
        $branchAccount->account_type = $data['account_type'];
        $branchAccount->branch_id = $data['branch'];
        $branchAccount->amount = $data['balance'];

        $branchAccount->save();

        $branchAccount['branch'] = Branch::find($data['branch'])['name'];

        if (!$request->ajax()) {
            return redirect()->route('branch_accounts.list')->with('success', _lang('Saved Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Successfully'), 'data' => $branchAccount, 'table' => '#branch_account_table']);
        }
    }


    public function show(Request $request, $id)
    {
        $account = BranchAccount::find($id);
        $transactions = BranchAccountTransaction::where('account_id', $id)->get();
        return view('backend.branch.branch_accounts.view', compact('account', 'transactions'));
        
    }
    
    public function edit(Request $request, $id)
    {
        $branchAccount = BranchAccount::find($id);
        if (!$request->ajax()) {
            return view('backend.branch.branch_accounts.edit', compact('branchAccount', 'id'));
        } else {
            return view('backend.branch.branch_accounts.edit', compact('branchAccount', 'id'));
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = Validator::make($request->all(), [
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|string|max:255',
            'branch' => 'required|exists:branches,id', 
            'balance' => 'required|numeric',
        ]);

        if ($validatedData->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validatedData->errors()->all()]);
            } else {
                return redirect()->route('branch_accounts.edit')
                    ->withErrors($validatedData)
                    ->withInput();
            }
        }

         $data = $validatedData->validated();
       
        $branchAccount = BranchAccount::where('id', $id)->update([
            'account_name' => $data['account_name'],
            'account_type' => $data['account_type'],
            'branch_id' => $data['branch'], 
            'amount' => $data['balance'],
        ]);

        $data['branch'] = Branch::find($data['branch'])['name'];

        if (!$request->ajax()) {
            return redirect()->route('branch_accounts.list')->with('success', _lang('Saved Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Successfully'), 'data' => $data, 'table' => '#branch_account_table']);
        }
    }

    
    public function destroy($id)
    {
        $branchAccount = BranchAccount::find($id);
        $branchAccount->delete();
        return redirect()->route('branch_accounts.index')->with('success', _lang('Deleted Successfully')); 
    }


    public function getAccountsByBranch(Request $request)
    {
        $branchAccounts = BranchAccount::where('branch_id', $request->branch_id)->get();
        return response()->json($branchAccounts);
    }
}
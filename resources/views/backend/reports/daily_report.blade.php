@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <span class="panel-title">{{ _lang('Daily Financial Report (INR)') }}</span>
            </div>

            <div class="card-body">

                <div class="report-params">
                    <form class="validate" method="post" action="{{ route('reports.daily_report') }}" autocomplete="off">
                        <div class="row">
                            {{ csrf_field() }}

                            <div class="col-xl-3 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Select Date') }}</label>
                                    <input type="text" class="form-control datepicker" name="date" id="date" value="{{ isset($date) ? $date : old('date') }}" readOnly="true" required>
                                </div>
                            </div>

                            <div class="col-xl-2 col-lg-4">
                                <button type="submit" class="btn btn-light btn-xs btn-block mt-26">{{ _lang('Submit') }}</button>
                            </div>
                    </form>

                </div>
            </div><!--End Report param-->

            @php $date_format = get_option('date_format','Y-m-d'); @endphp
           
            <div class="report-header">
				   <h4>{{ _lang('DATE : ')}}{{isset($date) ? date($date_format, strtotime($date)) : '' }}</h4>
				</div>

            @php 
                $total_credit = 0;
                $total_debit = 0;
                $total_expense = 0;
            @endphp

            <table class="table table-bordered report-table">
                <thead>
                    <th>{{ _lang('ACCOUNT NAME') }}</th>
                    <th>{{ _lang('SUB-ACCOUNT') }}</th>
                    <th>{{ _lang('TYPE') }}</th>
                    <th>{{ _lang('AMOUNT (INR)') }}</th>
                    <th>{{ _lang('AVAILABLE BALANCE (INR)') }}</th>
                </thead>
                <tbody>
                    <!-- Branch Account Details -->
                    @if(isset($branches))
                    @foreach($branches as $branch)
                    @if($branch->branchAccounts)
                    @foreach($branch->branchAccounts as $account)
                    @php
                    $creditTransaction = optional($account->branchAccountTransactions->first());
                    $totalCredited = $creditTransaction ? $creditTransaction->total_credited : 0;
                    $total_credit += $totalCredited
                    @endphp
                    <tr>
                        <td>{{ $branch->name }}</td>
                        <td>{{ $account->account_name }}</td>
                        <td>{{ _lang('CREDIT') }}</td>
                        <td> {{ _lang('₹ ')}}{{ number_format($totalCredited, 2) }}</td>
                        <td> {{ _lang('₹ ')}}{{ number_format($account->amount, 2) }}</td>
                    </tr>
                    @endforeach
                    @endif
                    @endforeach
                    @endif
                    <!-- Loan Details -->
                    @if(isset($totalRepayment))
                    @php
                    $total_credit += $totalRepayment
                    @endphp
                    <tr>
                        <td>{{ _lang('Loan Repayment') }}</td>
                        <td></td>
                        <td>{{ _lang('CREDIT') }}</td>
                        <td> {{ _lang('₹ ')}}{{ number_format($totalRepayment, 2)}}</td>
                        <td></td>
                    </tr>
                    @endif

                    @if(isset($totalLoanGiven))
                    @php
                    $total_debit += $totalLoanGiven
                    @endphp
                    <tr>
                        <td>{{ _lang('Loan Given') }}</td>
                        <td></td>
                        <td>{{ _lang('DEBIT') }}</td>
                        <td> {{ _lang('₹ ')}}{{number_format($totalLoanGiven, 2)}}</td>
                        <td></td>
                    </tr>
                    @endif
                    <!-- Other Expenses -->

                    @if(isset($otherExpences))
                    @foreach($otherExpences as $expense)
                    @php
                    $total_debit += $expense->amount;
                    $total_expense += $expense->amount;
                    @endphp
                    <tr>
                        <td>{{ $expense->expense_category->name }}</td>
                        <td></td>
                        <td>{{ _lang('DEBIT') }}</td>
                        <td> {{ _lang('₹ ')}}{{number_format($expense->amount, 2)}}</td>
                        <td></td>
                    </tr>
                    @endforeach
                    @endif

                </tbody>
            </table>
            <h4> Total Credit: {{ _lang('₹ ')}} {{ number_format($total_credit, 2) }}</h5>
            <h4> Total Debit:  {{ _lang('₹ ')}} {{ number_format($total_debit, 2) }}</h5>
            <h4> Total Expense Today:  {{ _lang('₹ ')}} {{ number_format($total_expense, 2)}}</h5>
        </div>
    </div>
</div>
</div>

@endsection
@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-xl-3 col-md-6">
		<div class="card mb-4 primary-card dashboard-card">
			<div class="card-body">
				<div class="d-flex">
					<div class="flex-grow-1">
						<h5>{{ _lang('Total Members') }}</h5>
						<h4 class="pt-1 mb-0"><b>{{ $total_customer }}</b></h4>
					</div>
					<div>
						<a href="{{ route('members.index') }}"><i class="ti-arrow-right"></i>&nbsp;{{ _lang('View') }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-3 col-md-6">
		<div class="card mb-4 success-card dashboard-card">
			<div class="card-body">
				<div class="d-flex">
					<div class="flex-grow-1">
						<h5>{{ _lang('Deposit Requests') }}</h5>
						<h4 class="pt-1 mb-0"><b>{{ request_count('deposit_requests') }}</b></h4>
					</div>
					<div>
						<a href="{{ route('deposit_requests.index') }}"><i class="ti-arrow-right"></i>&nbsp;{{ _lang('View') }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-3 col-md-6">
		<div class="card mb-4 warning-card dashboard-card">
			<div class="card-body">
				<div class="d-flex">
					<div class="flex-grow-1">
						<h5>{{ _lang('Withdraw Requests') }}</h5>
						<h4 class="pt-1 mb-0"><b>{{ request_count('withdraw_requests') }}</b></h4>
					</div>
					<div>
						<a href="{{ route('withdraw_requests.index') }}"><i class="ti-arrow-right"></i>&nbsp;{{ _lang('View') }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-3 col-md-6">
		<div class="card mb-4 danger-card dashboard-card">
			<div class="card-body">
				<div class="d-flex">
					<div class="flex-grow-1">
						<h5>{{ _lang('Pending Loans') }}</h5>
						<h4 class="pt-1 mb-0"><b>{{ request_count('pending_loans') }}</b></h4>
					</div>
					<div>
						<a href="{{ route('loans.index') }}"><i class="ti-arrow-right"></i>&nbsp;{{ _lang('View') }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-4 col-sm-5 mb-4">
		<div class="card h-100">
			<div class="card-header d-flex align-items-center">
				<span>{{ _lang('Expense Overview').' - '.date('M Y') }}</span>
			</div>
			<div class="card-body">
				<canvas id="expenseOverview"></canvas>
			</div>
		</div>
	</div>

	<div class="col-md-8 col-sm-7 mb-4">
		<div class="card h-100">
			<div class="card-header d-flex align-items-center">
				<span>{{ _lang('Deposit & Withdraw Analytics').' - '.date('Y')  }}</span>
				<select class="filter-select ml-auto py-0 auto-select" data-selected="{{ base_currency_id() }}">
					@foreach(\App\Models\Currency::where('status',1)->get() as $currency)
					<option value="{{ $currency->id }}" data-symbol="{{ currency($currency->name) }}">{{ $currency->name }}</option>
					@endforeach
				</select>
			</div>
			<div class="card-body">
				<canvas id="transactionAnalysis"></canvas>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 mb-4">
		<div class="card mb-4">
			<div class="card-header">
				{{ _lang('Daily Financial Report') }}
			</div>
			<div class="card-body">
				<div class="table-responsive">
				@php 
                $total_credit = 0;
                $total_debit = 0;
                $total_expense = 0;
            	@endphp

            <table class="table table-bordered">
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
</div>

<div class="row">
	<div class="col-md-12 mb-4">
		<div class="card mb-4">
			<div class="card-header">
				{{ _lang('Due Loan Payments') }}
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th class="text-nowrap">{{ _lang('Loan ID') }}</th>
								<th class="text-nowrap">{{ _lang('Member No') }}</th>
								<th class="text-nowrap">{{ _lang('Member') }}</th>
								<th class="text-nowrap">{{ _lang('Last Payment Date') }}</th>
								<th class="text-nowrap">{{ _lang('Due Repayments') }}</th>
								<th class="text-nowrap text-right">{{ _lang('Total Due') }}</th>
							</tr>
						</thead>
						<tbody>
							@if(count($due_repayments) == 0)
								<tr>
									<td colspan="5"><h6 class="text-center">{{ _lang('No Active Loan Available') }}</h6></td>
								</tr>
							@endif

							@foreach($due_repayments as $repayment)
							<tr>
								<td>{{ $repayment->loan->loan_id }}</td>
								<td>{{ $repayment->loan->borrower->member_no }}</td>
								<td>{{ $repayment->loan->borrower->name }}</td>
								<td class="text-nowrap">{{ $repayment->repayment_date }}</td>
								<td class="text-nowrap">{{ $repayment->total_due_repayment }}</td>
								<td class="text-nowrap text-right">{{ decimalPlace($repayment->total_due, currency($repayment->loan->currency->name)) }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="card mb-4">
			<div class="card-header">
				{{ _lang('Recent Transactions') }}
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered">
					<thead>
					    <tr>
						    <th>{{ _lang('Date') }}</th>
							<th>{{ _lang('Member') }}</th>
							<th class="text-nowrap">{{ _lang('Account Number') }}</th>
							<th>{{ _lang('Amount') }}</th>
							<th class="text-nowrap">{{ _lang('Debit/Credit') }}</th>
							<th>{{ _lang('Type') }}</th>
							<th>{{ _lang('Status') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
					@foreach($recent_transactions as $transaction)
						@php
						$symbol = $transaction->dr_cr == 'dr' ? '-' : '+';
						$class  = $transaction->dr_cr == 'dr' ? 'text-danger' : 'text-success';
						@endphp
						<tr>
							<td class="text-nowrap">{{ $transaction->trans_date }}</td>
							<td>{{ $transaction->member->name }}</td>
							<td>{{ $transaction->account->account_number }}</td>
							<td><span class="text-nowrap {{ $class }}">{{ $symbol.' '.decimalPlace($transaction->amount, currency($transaction->account->savings_type->currency->name)) }}</span></td>
							<td>{{ strtoupper($transaction->dr_cr) }}</td>
							<td>{{ str_replace('_',' ',$transaction->type) }}</td>
							<td>{!! xss_clean(transaction_status($transaction->status)) !!}</td>
							<td class="text-center"><a href="{{ route('transactions.show', $transaction->id) }}" target="_blank" class="btn btn-outline-primary btn-xs"><i class="ti-arrow-right"></i>&nbsp;{{ _lang('View') }}</a></td>
						</tr>
					@endforeach
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js-script')
<script src="{{ asset('public/backend/plugins/chartJs/chart.min.js') }}"></script>
<script src="{{ asset('public/backend/assets/js/dashboard.js?v=1.1') }}"></script>
@endsection

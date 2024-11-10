<table class="table table-bordered">
	<tr>
		<td>{{ _lang('Account Name') }}</td>
		<td>{{ $account->account_name }}</td>
	</tr>
	<tr>
		<td>{{ _lang('Account Type') }}</td>
		<td>{{ $account->account_type }}</td>
	</tr>
	<tr>
		<td>{{ _lang('Branch') }}</td>
		<td>{{ $account->branch->name }}</td>
	</tr>
	<tr>
		<td>{{ _lang('Balance') }}</td>
		<td>{{ $account->amount }}</td>
	</tr>
</table>

@if( count($transactions) > 0)

<table class="table table-bordered data-table">
	<thead>
		<tr>
			<th>{{ _lang('User Name') }}</th>
			<th>{{ _lang('Amount') }}</th>
			<th>{{ _lang('Transaction Type') }}</th>
			<th>{{ _lang('Date') }}</th>
		</tr>
	</thead>
	<tbody>
		@foreach($transactions as $transaction)
		<tr data-id="row_{{ $transaction->id }}">
			<td class='user_name'>{{ $transaction->user->name }}</td>
			<td class='amount'>{{ $transaction->amount }}</td>
			<td class='transaction_type'>{{ $transaction->transaction_type }}</td>
			<td class='transaction_date'>{{ \Carbon\Carbon::parse($transaction->timestamp)->format('d-m-Y H:i') }}</td>
		</tr>
		@endforeach
	</tbody>
</table>

@else

<h5 class="text-danger">{{ _lang('No transactions found.') }}</h5>

@endif
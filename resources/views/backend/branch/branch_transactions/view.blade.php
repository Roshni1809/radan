<table class="table table-bordered">
	<tr><td>{{ _lang('Branch Name') }}</td><td>{{ $transaction->branch->name }}</td></tr>
	<tr><td>{{ _lang('User Name') }}</td><td>{{ $transaction->user->name }}</td></tr>
	<tr><td>{{ _lang('Branch Account Name') }}</td><td>{{ $transaction->account->account_name }}</td></tr>
	<tr><td>{{ _lang('Amount') }}</td><td>{{ $transaction->amount }}</td></tr>
	<tr><td>{{ _lang('Date & Time ') }}</td><td>{{  \Carbon\Carbon::parse($transaction->timestamp)->format('d-m-Y H:i') }}</td></tr>

</table>


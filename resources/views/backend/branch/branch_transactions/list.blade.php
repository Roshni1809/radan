@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="card no-export">
		    <div class="card-header d-flex align-items-center">
				<span class="panel-title">{{ _lang('All Transactions') }}</span>
				<a class="btn btn-primary btn-xs ml-auto ajax-modal" data-title="{{ _lang('Add New Transaction') }}" href="{{ route('branch_account_transactions.create') }}"><i class="ti-plus"></i>&nbsp;{{ _lang('Add New Transaction') }}</a>
			</div>
			<div class="card-body">
				<table id="branche_transaction_table" class="table table-bordered data-table">
					<thead>
					    <tr>
						    <th>{{ _lang('Branch Name') }}</th>
							<th>{{ _lang('User Name') }}</th>
							<th>{{ _lang('Account Name') }}</th>
							<th>{{ _lang('Amount') }}</th>
							<th>{{ _lang('Transaction Type') }}</th>
							<th>{{ _lang('Date') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
					    @foreach($branchTransactions as $transaction)
					    <tr data-id="row_{{ $transaction->id }}">

							<td class='branch_name'>{{ $transaction->branch->name }}</td>
							<td class='user_name'>{{ $transaction->user->name }}</td>
							<td class='account_name'>{{ $transaction->account->account_name }}</td>
							<td class='amount'>{{ $transaction->amount }}</td>
							<td class='transaction_type'>{{ $transaction->transaction_type }}</td>
							<td class='transaction_date'>{{ \Carbon\Carbon::parse($transaction->updated_at)->format('d-m-Y H:i') }}</td>
							<td class="text-center">
								<span class="dropdown">
								  <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  {{ _lang('Action') }}
								  
								  </button>
								  <form action="{{ route('branch_account_transactions.destroy', $transaction['id']) }}" method="post">
									{{ csrf_field() }}
									<input name="_method" type="hidden" value="DELETE">

									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<!-- <a href="{{ route('branch_account_transactions.edit', $transaction['id']) }}" data-title="{{ _lang('Update Account') }}" class="dropdown-item dropdown-edit ajax-modal"><i class="ti-pencil-alt"></i>&nbsp;{{ _lang('Edit') }}</a> -->
										<a href="{{ route('branch_account_transactions.show', $transaction['id']) }}" data-title="{{ _lang('Account Details') }}" class="dropdown-item dropdown-view ajax-modal"><i class="ti-eye"></i>&nbsp;{{ _lang('View') }}</a>
										<button class="btn-remove dropdown-item" type="submit"><i class="ti-trash"></i>&nbsp;{{ _lang('Delete') }}</button>
									</div>
								  </form>
								</span>
							</td>
					    </tr>
					    @endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@endsection
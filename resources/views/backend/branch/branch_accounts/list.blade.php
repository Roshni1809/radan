@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="card no-export">
		    <div class="card-header d-flex align-items-center">
				<span class="panel-title">{{ _lang('All Branches Accounts') }}</span>
				<a class="btn btn-primary btn-xs ml-auto ajax-modal" data-title="{{ _lang('Add New Branch Account') }}" href="{{ route('branch_accounts.create') }}"><i class="ti-plus"></i>&nbsp;{{ _lang('Add New Account') }}</a>
			</div>
			<div class="card-body">
				<table id="branch_account_table" class="table table-bordered data-table">
					<thead>
					    <tr>
						    <th>{{ _lang('Account Name') }}</th>
							<th>{{ _lang('Account Type') }}</th>
							<th>{{ _lang('Branch') }}</th>
							<th>{{ _lang('Amount') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
					    @foreach($branchAccounts as $account)
					    <tr data-id="row_{{ $account->id }}">
							<td class='account_name'>{{ $account->account_name }}</td>
							<td class='account_type'>{{ $account->account_type }}</td>
							<td class='branch'>{{ $account->branch->name }}</td>
							<td class='amount'>{{ $account->amount }}</td>

							<td class="text-center">
								<span class="dropdown">
								  <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  {{ _lang('Action') }}
								  
								  </button>
								  <form action="{{ route('branch_accounts.destroy', $account['id']) }}" method="post">
									{{ csrf_field() }}
									<input name="_method" type="hidden" value="DELETE">

									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a href="{{ route('branch_accounts.edit', $account['id']) }}" data-title="{{ _lang('Update Account') }}" class="dropdown-item dropdown-edit ajax-modal"><i class="ti-pencil-alt"></i>&nbsp;{{ _lang('Edit') }}</a>
										<a href="{{ route('branch_accounts.show', $account['id']) }}" data-title="{{ _lang('Account Details') }}" class="dropdown-item dropdown-view ajax-modal"><i class="ti-eye"></i>&nbsp;{{ _lang('View Account') }}</a>
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
<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ route('branch_accounts.update', $id) }}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">

	<div class="row px-2">
		<div class="col-md-12">
			<div class="form-group">
			<label class="control-label">{{ _lang('Account Name') }}</label>
			<input type="text" class="form-control" name="account_name" value="{{ $branchAccount->account_name }}" required>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
			<label class="control-label">{{ _lang('Account Type') }}</label>
			<input type="text" class="form-control" name="account_type" value="{{ $branchAccount->account_type }}">
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
			<label class="control-label">{{ _lang('Branch') }}</label>
			<select class="form-control select2" name="branch" required>
					<option value="">{{ _lang('Select Branch') }}</option>
					@foreach(App\Models\Branch::all() as $branch)
						<option name="branch" value="{{ $branch->id }}"> ({{ $branch->name }})</option>
					@endforeach
			</select>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
			<label class="control-label">{{ _lang('Balance') }}</label>
            <input type="number" class="form-control" name="balance" value="{{ $branchAccount->amount }}">

			</div>
		</div>


		<div class="form-group">
			<div class="col-md-12">
				<button type="submit" class="btn btn-primary "><i class="ti-check-box"></i>&nbsp;{{ _lang('Update') }}</button>
			</div>
		</div>
	</div>
</form>


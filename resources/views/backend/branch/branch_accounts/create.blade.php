<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ route('branch_accounts.store') }}" enctype="multipart/form-data" data-reload="false">
	{{ csrf_field() }}
	<div class="row px-2">
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Account Name') }}</label>
				<input type="text" class="form-control" name="account_name" value="{{ old('account_name') }}" required>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Account Type') }}</label>
				<input type="text" class="form-control" name="account_type" value="{{ old('account_type') }}" required>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Branch') }}</label>
				<select class="form-control auto-select select2" data-selected="{{ old('branch_id') }}" name="branch" required>
				<option value="">{{ _lang('Select Branch') }}</option>
					@foreach(App\Models\Branch::all() as $branch)
					<option name="branch" value="{{ $branch->id }}"> ({{ $branch->name }})</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Opeaning Balance') }}</label>
				<input type="number" class="form-control" name="balance" value="{{ old('balance') }}" required>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<button type="submit" class="btn btn-primary "><i class="ti-check-box"></i>&nbsp;{{ _lang('Save') }}</button>
			</div>
		</div>
	</div>
</form>
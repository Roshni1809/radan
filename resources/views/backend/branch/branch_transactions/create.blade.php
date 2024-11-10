<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ route('branch_account_transactions.store') }}" enctype="multipart/form-data" data-reload="false">
	{{ csrf_field() }}
	<div class="row px-2">

		<!-- Branch Dropdown -->
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Branch') }}</label>
				<select class="form-control select2" id="branch-select" name="branch" required>
					<option value="">{{ _lang('Select Branch') }}</option>
					@foreach(App\Models\Branch::all() as $branch)
					<option value="{{ $branch->id }}"> ({{ $branch->name }})</option>
					@endforeach
				</select>
			</div>
		</div>

		<!-- Branch Account Dropdown (Initially Disabled) -->
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Branch Account') }}</label>
				<select class="form-control select2" id="branch-account-select" name="branch_account" required disabled>
					<option value="">{{ _lang('Select Account') }}</option>
				</select>
			</div>
		</div>
		<!-- Amount Input -->
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Amount') }}</label>
				<input type="number" class="form-control" name="amount" value="{{ old('Amount') }}" required>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Transaction Type') }}</label>
				<select class="form-control" name="transaction_type" required>
					<option value="">{{ _lang('Select Transaction Type') }}</option>
					<option value="debit">{{ _lang('Debit') }}</option>
					<option value="credit">{{ _lang('Credit') }}</option>
				</select>
			</div>
		</div>


		<!-- Submit Button -->
		<div class="col-md-12">
			<div class="form-group">
				<button type="submit" class="btn btn-primary "><i class="ti-check-box"></i>&nbsp;{{ _lang('Save') }}</button>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		// When a branch is selected
		$('#branch-select').on('change', function() {
			var branchId = $(this).val();

			// Disable the Branch Account dropdown while fetching data
			$('#branch-account-select').prop('disabled', true).empty().append('<option value="">{{ _lang('Select Account') }}</option>');

			// Only proceed if a valid branch ID is selected
			if (branchId) {
				// Make an AJAX request to fetch branch accounts
				$.ajax({
					url: '{{ route('branch.accounts') }}', // Define this route in your web.php
					type: 'GET',
					data: {
						branch_id: branchId
					},
					success: function(response) {
						// Populate the Branch Account dropdown with accounts
						if (response.length > 0) {
							$.each(response, function(index, account) {
								$('#branch-account-select').append('<option value="' + account.id + '">' + account.account_name + '</option>');
							});
							$('#branch-account-select').prop('disabled', false); // Enable dropdown
						}
					},
					error: function() {
						alert('Error fetching branch accounts. Please try again.');
					}
				});
			}
		});
	});
</script>
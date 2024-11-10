@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<span class="panel-title">{{ _lang('Update Loan Information') }}</span>
			</div>
			<div class="card-body">
				@if($loan->status == 1)
					<div class="alert alert-warning">
						<strong>{{ _lang('Loan has already been approved. You can only change the description and remarks.') }}</strong>
					</div>
				@endif
				<form method="post" class="validate" autocomplete="off" action="{{ route('loans.update', $id) }}" enctype="multipart/form-data">
					{{ csrf_field()}}
					<input name="_method" type="hidden" value="PATCH">
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Loan ID') }}</label>
								<input type="text" class="form-control" name="loan_id" value="{{ old('loan_id', $loan->loan_id) }}" {{ $loan->status == 1 ? 'disabled' : 'required' }}>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Loan Product') }}</label>
								<select class="form-control auto-select select2" data-selected="{{ old('loan_product_id', $loan->loan_product_id) }}" id="loan_product_id" name="loan_product_id" {{ $loan->status == 1 ? 'disabled' : 'required' }}>
									<option value="">{{ _lang('Select One') }}</option>
									@foreach(\App\Models\LoanProduct::active()->get() as $loanProduct)
										<option value="{{ $loanProduct->id }}" data-penalties="{{ $loanProduct->late_payment_penalties }}">{{ $loanProduct->name }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Borrower') }}</label>
								<select class="form-control auto-select select2" data-selected="{{ old('borrower_id', $loan->borrower_id) }}" name="borrower_id" id="borrower_id" {{ $loan->status == 1 ? 'disabled' : 'required' }}>
									<option value="">{{ _lang('Select One') }}</option>
									@foreach(\App\Models\Member::all() as $member)
										<option value="{{ $member->id }}" data-branch-id="{{ $member->branch_id }}" data-branch-name="{{ $member->branch->name }}">
											{{ $member->first_name . ' ' . $member->last_name . ' (' . $member->member_no . ')' }}
										</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Currency') }}</label>
								<select class="form-control auto-select" data-selected="{{ old('currency_id', $loan->currency_id) }}" name="currency_id" {{ $loan->status == 1 ? 'disabled' : 'required' }}>
									<option value="">{{ _lang('Select One') }}</option>
									{{ create_option('currency', 'id', 'name', '', ['status=' => 1]) }}
								</select>
							</div>
						</div>

						<div class="col-lg-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Branch') }}</label>
								<input type="text" class="form-control" name="branch_id" id="branch_id" value="{{ old('branch_id', $loan->branch->name) }}" readonly>
							</div>
						</div>

						<div class="col-lg-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Branch Account') }}</label>
								<select class="form-control auto-select select2" data-selected="{{ old('branch_account_id', $loan->branch_account_id) }}" id="branch_account_id" name="branch_account_id" required disabled>
									<option value="">{{ _lang('Select Branch Account') }}</option>
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('First Payment Date') }}</label>
								<input type="text" class="form-control datepicker" name="first_payment_date" value="{{ old('first_payment_date', $loan->getRawOriginal('first_payment_date')) }}" {{ $loan->status == 1 ? 'disabled' : 'required' }}>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Release Date') }}</label>
								<input type="text" class="form-control datepicker" name="release_date" value="{{ old('release_date', $loan->getRawOriginal('release_date')) }}" {{ $loan->status == 1 ? 'disabled' : 'required' }}>
							</div>
						</div>

						<div class="col-lg-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Document Charges') }}</label>
								<div class="input-group">
									<input type="text" class="form-control float-field" name="document_charges" value="{{ old('document_charges') }}" id="document_charges" required>
									<div class="input-group-append">
										<span class="input-group-text">%</span>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Other Charges') }}</label>
								<div class="input-group">
									<input type="text" class="form-control float-field" name="other_chaerges" value="{{ old('other_chaerges') }}" id="other_chaerges" required>
									<div class="input-group-append">
										<span class="input-group-text">%</span>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Notes For Other Charges') }}</label>
								<textarea class="form-control" name="description">{{ old('notes_for_other_charges') }}</textarea>
							</div>
						</div>

						<!-- Custom Fields (if any) -->
						@if(!$customFields->isEmpty())
							@php $customFieldsData = json_decode($loan->custom_fields, true); @endphp
							@foreach($customFields as $customField)
								<div class="{{ $customField->field_width }}">
									<div class="form-group">
										<label class="control-label">{{ $customField->field_name }}</label>	
										{!! xss_clean(generate_input_field($customField, $customFieldsData[$customField->field_name]['field_value'] ?? old($customField->field_name))) !!}
									</div>
								</div>
							@endforeach
                        @endif

						<!-- Description and Remarks -->
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Description') }}</label>
								<textarea class="form-control" name="description">{{ old('description', $loan->description) }}</textarea>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Remarks') }}</label>
								<textarea class="form-control" name="remarks">{{ old('remarks', $loan->remarks) }}</textarea>
							</div>
						</div>

						<!-- Submit Button -->
						<div class="col-md-12">
							<div class="form-group">
								<button type="submit" class="btn btn-primary">{{ _lang('Update Changes') }}</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js-script')
<script>
(function ($) {

	$(document).on('change', '#loan_product_id', function(){
		$("#late_payment_penalties").val($(this).find(':selected').data('penalties'));
	});

	$('#branch_id').prop('readonly', true);

	$(document).ready(function() {
		var branchId = "{{ $loan->branch_id ?? '' }}";
		var selectedBranchAccountId = "{{ $loan->branch_account_id ?? '' }}";

		if (branchId) {
			fetchBranchAccounts(branchId, selectedBranchAccountId);
		}
	});

	function fetchBranchAccounts(branchId, selectedAccountId = null) {
		$('#branch_account_id').prop('disabled', true).empty().append('<option value="">{{ _lang('Select Branch Account') }}</option>');

		if (branchId) {
			$.ajax({
				url: '{{ route('branch.accounts') }}',
				type: 'GET',
				data: { branch_id: branchId },
				success: function(response) {
					if (response.length > 0) {
						$.each(response, function(index, account) {
							var selected = (selectedAccountId == account.id) ? 'selected' : '';
							$('#branch_account_id').append('<option value="' + account.id + '" ' + selected + '>' + account.account_name + '</option>');
						});
						$('#branch_account_id').prop('disabled', false); // Enable dropdown
					}
				},
				error: function() {
					alert('Error fetching branch accounts. Please try again.');
				}
			});
		}
	}

	$('#borrower_id').on('change', function() {
		var borrowerId = $(this).val();
		var branchId = $(this).find(':selected').data('branch-id');
		var branchName = $(this).find(':selected').data('branch-name');

		if (borrowerId) {
			$('#branch_id').val(branchName).prop('readonly', true);

			fetchBranchAccounts(branchId);
		} else {
			$('#branch_id').val('');
			$('#branch_account_id').empty().append('<option value="">{{ _lang('Select Branch Account') }}</option>');
			$('#branch_account_id').prop('disabled', true);
		}
	});

})(jQuery);
</script>
@endsection

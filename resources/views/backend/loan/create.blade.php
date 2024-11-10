@extends('layouts.app')

@section('content')
<div class="row">
	<div class="{{ $alert_col }}">
		<div class="card">
			<div class="card-header text-center">
				<span class="panel-title">{{ _lang('Add New Loan') }}</span>
			</div>
			<div class="card-body">
				<form method="post" class="validate" autocomplete="off" action="{{ route('loans.store') }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Loan ID') }}</label>
								<input type="text" class="form-control" name="loan_id" id="loan_id" value="{{ old('loan_id') }}" required readonly>
							</div>
						</div>

						<div class="col-lg-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Loan Product') }}</label>
								<select class="form-control auto-select select2" data-selected="{{ old('loan_product_id') }}" name="loan_product_id" id="loan_product_id" required>
									<option value="">{{ _lang('Select One') }}</option>
									@foreach(\App\Models\LoanProduct::active()->get() as $loanProduct)
									<option value="{{ $loanProduct->id }}" data-penalties="{{ $loanProduct->late_payment_penalties }}" data-loan-id="{{ $loanProduct->loan_id_prefix.$loanProduct->starting_loan_id }}">{{ $loanProduct->name }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="col-lg-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Borrower') }}</label>
								<select class="form-control auto-select select2" data-selected="{{ old('borrower_id') }}" name="borrower_id" id="borrower_id" required>
									<option value="">{{ _lang('Select One') }}</option>
									@foreach(\App\Models\Member::all() as $member )
									<option value="{{ $member->id }}" data-branch-id="{{ $member->branch_id }}" data-branch-name="{{ $member->branch->name }}">{{ $member->first_name.' '.$member->last_name .' ('. $member->member_no . ')' }}</option>									@endforeach
								</select>
							</div>
						</div>

					<div class="col-lg-6">
    					<div class="form-group">
        					<label class="control-label">{{ _lang('Currency') }}</label>
        					<select class="form-control auto-select" data-selected="{{ old('currency_id', $baseCurrencyId) }}" name="currency_id" id="currency_id" required>
            				<option value="">{{ _lang('Select One') }}</option>
           	 				{{ create_option('currency','id','name','',array('status=' => 1)) }}
        					</select>
    					</div>
					</div>
					<div class="col-lg-6">
    <div class="form-group">
        <label class="control-label">{{ _lang('Branch') }}</label>
        <input type="text" class="form-control" id="branch_name" value="" readonly>
        <input type="hidden" name="branch_id" id="branch_id" value="{{ old('branch_id') }}">
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group">
        <label class="control-label">{{ _lang('Branch Account') }}</label>
        <select class="form-control auto-select select2" data-selected="{{ old('branch_account_id') }}" id="branch_account_id" name="branch_account_id" required disabled>
            <option value="">{{ _lang('Select Branch Account') }}</option>
        </select>
    </div>
</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('First Payment Date') }}</label>
								<input type="text" class="form-control datepicker" name="first_payment_date" value="{{ old('first_payment_date') }}" required>
							</div>
						</div>

						<div class="col-lg-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Release Date') }}</label>
								<input type="text" class="form-control datepicker" name="release_date" value="{{ old('release_date') }}" required>
							</div>
						</div>

						<div class="col-lg-6">  
   <div class="form-group">  
      <label class="control-label">{{ _lang('Applied Amount') }}</label>  
      <input type="text" class="form-control" name="applied_amount" value="{{ old('applied_amount') }}" id="applied_amount" required>  
      <span id="amount_in_words"></span>  
   </div>  
</div>  

						<div class="col-lg-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Late Payment Penalties') }}</label>
								<div class="input-group">
									<input type="text" class="form-control float-field" name="late_payment_penalties" value="{{ old('late_payment_penalties') }}" id="late_payment_penalties" required>
									<div class="input-group-append">
										<span class="input-group-text">%</span>
									</div>
								</div>
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

						@if(! $customFields->isEmpty())
							@foreach($customFields as $customField)
							<div class="{{ $customField->field_width }}">
								<div class="form-group">
									<label class="control-label">{{ $customField->field_name }}</label>	
									{!! xss_clean(generate_input_field($customField)) !!}
								</div>
							</div>
							@endforeach
                        @endif

						<div class="col-lg-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Attachment') }}</label>
								<input type="file" class="dropify" name="attachment" value="{{ old('attachment') }}">
							</div>
						</div>

						<div class="col-lg-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Description') }}</label>
								<textarea class="form-control" name="description">{{ old('description') }}</textarea>
							</div>
						</div>

						<div class="col-lg-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Remarks') }}</label>
								<textarea class="form-control" name="remarks">{{ old('remarks') }}</textarea>
							</div>
						</div>

						<div class="col-lg-12">
							<div class="form-group">
								<button type="submit" class="btn btn-primary"><i class="ti-check-box"></i>&nbsp;{{ _lang('Submit') }}</button>
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

	$('#applied_amount').on('input', function() {
		let value = $(this).val().replace(/[^0-9.]/g, '');
		let currency = $("#currency_id").find(':selected').text();
		console.log(currency);
		if (value !== '') {
			if ( currency === 'INR') {
            	let formattedValue = Number(value).toLocaleString('en-IN');
            	$(this).val(formattedValue);
			} else {
                $(this).val(value);
            }
        } else {
            $(this).val('');
        }
    });

$(document).on('change', '#loan_product_id', function(){
	$("#late_payment_penalties").val($(this).find(':selected').data('penalties'));

	if($(this).val() != ''){
		var loanID = $(this).find(':selected').data('loan-id');
		loanID != '' ? $("#loan_id").val(loanID) : 
		Swal.fire({
			text: "{{ _lang('Please set starting loan ID to your selected loan product before creating new loan!') }}",
			icon: "error",
			confirmButtonColor: "#e74c3c",
			confirmButtonText: "{{ _lang('Close') }}",
		});
	} else {
		$("#loan_id").val('');
	}
});

$(document).ready(function() {
	var oldBranchId = "{{ old('branch_id') }}";
	var oldBranchAccountId = "{{ old('branch_account_id') }}";

	if (oldBranchId) {
		var branchName = "{{ \App\Models\Branch::find(old('branch_id'))->name ?? '' }}";
		$("#branch_name").val(branchName);  
		$("#branch_id").val(oldBranchId);  

		fetchBranchAccounts(oldBranchId, oldBranchAccountId);
	}
});

function fetchBranchAccounts(branchId, selectedAccountId = null) {
	$('#branch_account_id').prop('disabled', true).empty().append('<option value="">{{ _lang('Select Account') }}</option>');

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
					$('#branch_account_id').prop('disabled', false);
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
		
		$('#branch_name').val(branchName);

		$('#branch_id').val(branchId);

		fetchBranchAccounts(branchId);
	} else {
		$('#branch_name').val('');
		$('#branch_id').val('');
		$('#branch_account_id').empty().append('<option value="">{{ _lang('Select Branch Account') }}</option>');
		$('#branch_account_id').prop('disabled', true);
	}
});

})(jQuery);

</script>
@endsection

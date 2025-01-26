@extends('layout.app')
@section('title')
    case
@endsection
@section('main')
    <div class="container p-4">


        <form action="{{ route('case') }}" method="POST" class="needs-validation card width-m mx-auto" id="caseForm"
            novalidate>
            @csrf
            <div class="card-header text-bg-primary">
                <h5 class="mb-0">Case</h5>
            </div>
            <div class="container card-body">
                <div class="row mb-4">
                    <div class="col-lg-6">

                        <label class="form-label">Case Number</label>
                        <input type="text" class="form-control @error('case_number') is-invalid @enderror"
                            name="case_number" autocomplete="off" value="{{ old('case_number') }}" required>
                        <div class="invalid-feedback ">
                            @if ($errors->has('case_number'))
                                @error('case_number')
                                    {{ $message }}
                                @enderror
                            @else
                                Case Number is Required.
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 ">
                        <label class="form-label d-block">Case Type</label>
                        <div class="btn-group radio-group case_type-group @error('case_type') is-invalid @enderror"
                            role="group" aria-label="Basic radio toggle button group">
                            @foreach (config('enums.case_types') as $case_type)
                                <input type="radio" class="btn-check" value="{{ $case_type }}" name="case_type"
                                    id="btn{{ $case_type }}" autocomplete="off" required
                                    @if (old('case_type') == $case_type) checked @endif>
                                <label class="btn btn-outline-primary text-uppercase"
                                    for="btn{{ $case_type }}">{{ Str::headline($case_type) }}</label>
                            @endforeach
                        </div>
                        @error('case_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="invalid-feedback">
                            Case Type is Required.
                        </div>

                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <label class="form-label">Title</label>
                        <textarea class="form-control  @error('case_title') is-invalid @enderror" name="case_title" rows="3"
                            autocomplete="off" required>{{ old('case_title') }}</textarea>
                        @error('case_title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="invalid-feedback">
                            Title is Required.
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-6 ">
                        <label class="form-label d-block">Client</label>
                        <div id="clientInfo" class="info">
                            <div id="clientId" class="id"></div>
                            <div id="clientName" class="name"></div>
                            <button type="button" class="btn btn-primary infoBtn searchBtn" id="clientSearch"
                                data-bs-toggle="modal" data-bs-target="#getClientsModal"><i
                                    class="bi bi-search"></i></button>
                            <button type="button" class="btn btn-danger infoBtn clearBtn" id="clientClear"><i
                                    class="bi bi-x-lg"></i></button>
                        </div>
                        <input type="text" class="form-control visually-hidden" name="client_id" id="clientIdInp"
                            autocomplete="off" required>
                        <div class="invalid-feedback">
                            Client is Required.
                        </div>

                    </div>
                    <div class="col-lg-6">
                        <label class="form-label d-block">Role</label>
                        <div class="btn-group radio-group party_role-group  @error('party_role') is-invalid @enderror"
                            role="group" aria-label="Basic radio toggle button group">
                            @foreach (config('enums.party_roles') as $party_role)
                                <input type="radio" class="btn-check" value="{{ $party_role }}" name="party_role"
                                    id="btn{{ $party_role }}" autocomplete="off" required
                                    @if (old('party_role') == $party_role) checked @endif>
                                <label class="btn btn-outline-primary text-uppercase"
                                    for="btn{{ $party_role }}">{{ $party_role }}</label>
                            @endforeach
                        </div>
                        @error('party_role')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="invalid-feedback">
                            Role is Required.
                        </div>

                    </div>
                </div>
                <div class="row  mb-4">
                    <div class="col-lg-12">
                        <label for="opposingPartyInp" class="form-label">Opposing Party</label>
                        <input type="text" class="form-control  @error('opposing_party') is-invalid @enderror"
                            id="opposingPartyInp" name="opposing_party" autocomplete="off"
                            value="{{ old('opposing_party') }}" required>
                        @error('opposing_party')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="invalid-feedback">
                            Opposing Party is Required.
                        </div>
                    </div>
                </div>
                <div class="row  mb-4">
                    <div class="col-lg-6">
                        <label class="form-label d-block">Associate</label>

                        <div id="associateInfo" class="info">
                            <div id="associateId" class="id"></div>
                            <div id="associateName" class="name"></div>
                            <button type="button" class="btn btn-primary infoBtn searchBtn" id="associateSearch"
                                data-bs-toggle="modal" data-bs-target="#getAssociatesModal"><i
                                    class="bi bi-search"></i></button>
                            <button type="button" class="btn btn-danger infoBtn clearBtn" id="associateClear"><i
                                    class="bi bi-x-lg"></i></button>
                        </div>
                        <input type="text" class="form-control visually-hidden" type="hidden" name="associate_id"
                            id="associateIdInp" autocomplete="off" required>
                        <div class="invalid-feedback">
                            Associate is Required.
                        </div>
                    </div>
                </div>
                {{--                 
                <div class="row mb-4">
                    <div class="col-lg-12 mb-4">
                        <div class="mb-2  d-flex justify-content-between">
                            <span>Administrative Fee Deposits</span>
                            <button type="button" class="btn btn-primary" id="addAdminFeeDepositForm"><i
                                    class="bi bi-plus-lg"></i></button>
                        </div>
                        <div class="container card">
                            <div id="adminFeeDepositCon" class="row card-body"></div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="mb-2  d-flex justify-content-between">
                            <span>Administrative Fees</span>
                            <button type="button" class="btn btn-primary" id="addAdminFeeForm"><i
                                    class="bi bi-plus-lg"></i></button>
                        </div>
                        <div class="container card">
                            <div id="adminFeesCon" class="row card-body"></div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="mb-2  d-flex justify-content-between">
                            <span>Hearings</span>
                            <button type="button" class="btn btn-primary" id="addHearingForm"><i
                                    class="bi bi-plus-lg"></i></button>
                        </div>

                        <div class="container card">
                            <div id="hearingsCon" class="row card-body"></div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="mb-2  d-flex justify-content-between">
                            <span>Billings</span>
                            <button type="button" class="btn btn-primary" id="addBillingForm"><i
                                    class="bi bi-plus-lg"></i></button>
                        </div>

                        <div class="container card">
                            <div id="billingsCon" class="row card-body"></div>
                        </div>
                    </div>
                </div> --}}

                <div class="row">
                    <div class="justify-content-end d-flex">
                        <button class="btn btn-success px-4">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <x-get-clients-modal />
    <x-get-associates-modal />
    <x-get-branches-modal />

    <script>
        $('#caseForm').on('submit', function(e) {

            checkRadioGroupValidation('case_type')
            checkRadioGroupValidation('party_role')

        });
        $('.btn-check[type=radio]').on('change', function(e) {
            name = $(this).attr('name')
            checkRadioGroupValidation(name)

        })

        function checkRadioGroupValidation(name) {
            if ($(`input[name="${name}"][type=radio]:checked`).size() <= 0) {
                $(`.${name}-group`).addClass('is-invalid')
            } else {
                $(`.${name}-group`).removeClass('is-invalid')
            }
        }

        function selectClient(client) {
            $('#clientIdInp').val(client.id)
            $('#clientId').text(client.id)
            $('#clientName').text(client.first_name + ' ' + client.last_name + (client.suffix ? ' ' + client.suffix : ''))

        }
        $(document).on('click', '#clientClear', function(e) {
            $('#clientIdInp').val('')
            $('#clientId').text('')
            $('#clientName').text('')
        });

        function selectAssociate(associate) {
            $('#associateIdInp').val(associate.id)
            $('#associateId').text(associate.id)
            $('#associateName').text(associate.first_name + ' ' + associate.last_name + (associate.suffix ? ' ' + associate
                .suffix :
                ''))
        }
        $(document).on('click', '#associateClear', function(e) {
            $('#associateIdInp').val('')
            $('#associateId').text('')
            $('#associateName').text('')
        });


        $(document).on('click', '#addAdminFeeDepositForm', function() {
            $('#adminFeeDepositCon').append(`<div class=" col-lg-6 mb-4 addFormCon">
                                    <div class="card">
                                         <div class="card-header d-flex justify-content-between">
                                            <div class="num"></div>
                                            <div class="close"><button type="button" class="btn-close close"
                                                    aria-label="Close"></button></div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <label class="form-label">Payment Type</label>
                                                <select class="form-select" name="admin_deposit_payment_type_id[]"
                                                    required>
                                                    @foreach ($paymentTypes as $paymentType)
                                                        <option value="{{ $paymentType->id }}">
                                                            {{ $paymentType->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label d-block">Amount</label>
                                                <x-input-money name="admin_deposit_amount[]" value="0" />
                                            </div>
                                            <div>
                                                <label class="form-label ">Date</label>
                                                <input type="text" class="date-picker form-control"
                                                    name="admin_deposit_date[]" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>`)
            reInitDatePicker('#adminFeeDepositCon')
        })
        $(document).on('click', '#adminFeeDepositCon .close', function(e) {
            $(this).closest('.col-lg-6').remove();
        })
        $(document).on('click', '#addAdminFeeForm', function() {

            $('#adminFeesCon').append(`<div class=" col-lg-6 mb-4 addFormCon">
                                    <div class="card ">
                                        <div class="card-header d-flex justify-content-between">
                                            <div class="num"></div>
                                            <div class="close"><button type="button" class="btn-close close"
                                                    aria-label="Close"></button></div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <label class="form-label">Category</label>
                                                <select class="form-select adminFeeCategorySelect"
                                                    name="admin_fee_category_id[]" required>
                                                     <option disabled selected></option>
                                                    @foreach ($adminFeeCategories as $adminFeeCategory)
                                                        <option value="{{ $adminFeeCategory->id }}"
                                                            data-amount="{{ $adminFeeCategory->amount }}">
                                                            {{ $adminFeeCategory->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">Amount</label>
                                                <x-input-money name="admin_fee_amount[]" />
                                            </div>
                                            <div>
                                                <label class="form-label">Date</label>
                                                <input type="text" class="date-picker form-control" name="admin_fee_date[]" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>`)
            reInitDatePicker('#adminFeesCon')
        });

        $(document).on('click', '#adminFeesCon .close', function(e) {
            $(this).closest('.col-lg-6').remove();
        })
        $(document).on('change', '#adminFeesCon .adminFeeCategorySelect', function(e) {
            var amount = e.target.options[e.target.selectedIndex].dataset.amount;
            var wholeNumber = Math.floor(amount);
            var decimalNumber = Math.round((amount - wholeNumber) * 100);

            var moneyGroup = $(this).closest('.card').find('.money-group-con');
            moneyGroup.find('input.whole-part').val(wholeNumber);
            moneyGroup.find('input.decimal-part').val(('0' + decimalNumber).slice(-2));

            moneyGroup.find('input.whole-part, input.decimal-part').each(function() {
                updateHiddenValue(this);
            });
        });

        $(document).on('click', '#addHearingForm', function() {
            let uniqueId = getRandom();
            $('#hearingsCon').append(`<div class=" col-lg-12 mb-4 addFormCon" id="${uniqueId}">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <div class="num"></div>
                                                <div class="close"><button type="button" class="btn-close close"
                                                        aria-label="Close"></button></div>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <label class="form-label">Title</label>
                                                    <input type="text" class="form-control" name="hearing_title[]"
                                                        required>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label d-block">Court Branch</label>
                                                    <input type="hidden" name="hearing_court_branch_id[]"
                                                        class="courtBranchIdInp">
                                                    <div class="info courtBranchInfo">
                                                        <div class="id"></div>
                                                        <div class="name">
                                                            <nav aria-label="breadcrumb">
                                                                <ol class="breadcrumb mb-0">
                                                                    <li class="breadcrumb-item cBRegion"></li>
                                                                    <li class="breadcrumb-item cBCity"></li>
                                                                    <li class="breadcrumb-item cBType"></li>
                                                                    <li class="breadcrumb-item cBBranch"></li>
                                                                </ol>
                                                            </nav>
                                                        </div>
                                                        <button type="button" class="btn btn-primary infoBtn searchBtn courtBranchSearch"
                                                            data-bs-toggle="modal" data-id="${uniqueId}" 
                                                            data-bs-target="#getCourtBranchesModal"><i
                                                                class="bi bi-search"></i></button>
                                                        <button type="button" class="btn btn-danger infoBtn clearBtn courtBranchClear" data-id="${uniqueId}"><i
                                                                class="bi bi-x-lg"></i></button>
                                                    </div>

                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label">Date</label>
                                                    <input type="text" class="date-picker date-time-picker form-control"
                                                        name="hearing_date[]" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`)
            reInitDatePicker('#hearingsCon')
        });

        function selectCourtBranch(courtBranch, hearingCardId) {
            $(`#${hearingCardId} .courtBranchIdInp`).val(courtBranch.id)
            $(`#${hearingCardId} .courtBranchInfo .id`).text(courtBranch.id)
            $(`#${hearingCardId} .courtBranchInfo .cBRegion`).text(courtBranch.region)
            $(`#${hearingCardId} .courtBranchInfo .cBCity`).text(courtBranch.city)
            $(`#${hearingCardId} .courtBranchInfo .cBType`).text(courtBranch.type)
            $(`#${hearingCardId} .courtBranchInfo .cBBranch`).text(courtBranch.branch)
        }
        $(document).on('click', '.courtBranchClear', function(e) {
            let hearingCardId = $(this).data('id')
            $(`#${hearingCardId} .courtBranchIdInp`).val('')
            $(`#${hearingCardId} .id`).text('')
            $(`#${hearingCardId} span`).text('')
        });
        $(document).on('click', '#hearingsCon .close', function(e) {
            $(this).closest('.col-lg-12').remove();
        })
        $(document).on('click', '#addBillingForm', function() {
            $('#billingsCon').append(`<div class=" col-lg-12 mb-4 addFormCon">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <div class="num"></div>
                                                <div class="close"><button type="button" class="btn-close close"
                                                        aria-label="Close"></button></div>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <label class="form-label">Title</label>
                                                    <input type="text" class="form-control" name="billing_title[]"
                                                        required>
                                                </div>
                                                 <div class="col-lg-6 mb-2">
                                                        <label class="form-label d-block">Amount</label>
                                                        <x-input-money name="billing_amount[]" />

                                                    </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label class="form-label">Billing Date</label>
                                                        <input type="text" class="date-picker form-control"
                                                            name="billing_date[]" required>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label class="form-label">Due Date</label>
                                                        <input type="text" class="date-picker form-control"
                                                            name="billing_due_date[]" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`)
            reInitDatePicker('#billingsCon')
        });
        $(document).on('click', '#billingsCon .close', function(e) {
            $(this).closest('.col-lg-12').remove();
        })
    </script>
@endsection

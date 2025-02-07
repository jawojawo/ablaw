@extends('layout.app')
@section('title')
    case
@endsection
@section('main')
    <div class="container p-4">


        <form action="{{ route('case') }}" method="POST" class="needs-validation card mx-auto" id="caseForm" novalidate>
            @csrf
            <div class="card-header text-bg-primary">
                <h5 class="mb-0">Case</h5>
            </div>
            <div class="container card-body">
                <div class="row mb-4">
                    <div class="col-lg-6">

                        <label class="form-label">Case Number <span class="text-danger">*</span></label>
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
                        <label class="form-label d-block">Case Type <span class="text-danger">*</span></label>
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
                        <label class="form-label">Title <span class="text-danger">*</span></label>
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
                <div class="d-flex mb-4 gap-4">
                    <div class="flex-grow-1">
                        <label class="form-label d-block">Client <span class="text-danger">*</span></label>
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
                    <div class=" flex-shrink-1">
                        <label class="form-label d-block">Role <span class="text-danger">*</span></label>
                        <select class="form-select @error('party_role') is-invalid @enderror" name="party_role" required
                            autocomplete="off">
                            <option value="" selected disabled>Select Role</option>
                            @foreach (config('enums.party_roles') as $party_role)
                                <option value="{{ $party_role }}" @if (old('party_role') == $party_role) selected @endif>
                                    {{ $party_role }}</option>
                            @endforeach
                        </select>
                        {{-- <div class="btn-group radio-group party_role-group  @error('party_role') is-invalid @enderror"
                            role="group" aria-label="Basic radio toggle button group">
                            @foreach (config('enums.party_roles') as $party_role)
                                <input type="radio" class="btn-check" value="{{ $party_role }}" name="party_role"
                                    id="btn{{ $party_role }}" autocomplete="off" required
                                    @if (old('party_role') == $party_role) checked @endif>
                                <label class="btn btn-outline-primary text-uppercase"
                                    for="btn{{ $party_role }}">{{ $party_role }}</label>
                            @endforeach
                        </div> --}}
                        @error('party_role')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="invalid-feedback">
                            Role is Required.
                        </div>

                    </div>

                    <div class="flex-grow-1 ">
                        <label class="form-label d-block">Associates <span class="text-danger">*</span></label>
                        <div id="associateInfo" class="info">
                            <div id="associateId" class="id"></div>
                            <div id="associateName" class="name"></div>
                            <button type="button" class="btn btn-primary infoBtn searchBtn" id="associateSearch"
                                data-bs-toggle="modal" data-bs-target="#getAssociatesModal"><i
                                    class="bi bi-plus-lg"></i></button>
                            <button type="button" class="btn btn-danger infoBtn clearBtn" id="associateClear"><i
                                    class="bi bi-x-lg"></i></button>
                        </div>

                        <ul class="list-group associate-list mt-2"></ul>

                        {{-- <input type="text" class="form-control visually-hidden" type="hidden" name="associate_id"
                            id="associateIdInp" autocomplete="off" required> --}}
                        
                        <div class="invalid-feedback">
                            Associate is Required.
                        </div>
                    </div>
                </div>
                <div class="row  mb-4">
                    <div class="col-4">
                        <x-contact.create />
                    </div>
                    <div class="col-lg-8">
                        <div>
                            <label for="opposingPartyInp" class="form-label">Opposing Party <span
                                    class="text-danger">*</span></label>
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
                </div>
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
            $('#clientName').text(client.name)

        }
        @if ($client)
            selectClient({!! $client !!})
        @endif

        $(document).on('click', '#clientClear', function(e) {
            $('#clientIdInp').val('')
            $('#clientId').text('')
            $('#clientName').text('')
        });

        function selectAssociate(associate) {
            if ($(`#associate-${associate.id}`).length > 0) {
                return
            }
            $('.associate-list').append(`
            <li class="list-group-item d-flex gap-2 align-items-center justify-content-between  p-0" id="associate-${associate.id}">
                <div class="d-flex gap-2 align-items-center">
                    <div class="associate-id px-3 py-2 border-end">${associate.id}</div>
                    <div class="associate-name">${associate.name}</div>
                </div>
                <button type="button" class="btn  text-danger clearAssociateBtn"><i
                        class="bi bi-x-lg"></i></button>
                <input type="hidden" name="associate_ids[]" value="${associate.id}">
            </li>
            `)
        }
        $(document).on('click', '.clearAssociateBtn', function(e) {
            $(this).closest('li').remove();
        });
        // function selectAssociate(associate) {
        //     $('#associateIdInp').val(associate.id)
        //     $('#associateId').text(associate.id)
        //     $('#associateName').text(associate.name)
        // }
        // $(document).on('click', '#associateClear', function(e) {
        //     $('#associateIdInp').val('')
        //     $('#associateId').text('')
        //     $('#associateName').text('')
        // });


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
    </script>
@endsection

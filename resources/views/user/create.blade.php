@extends('layout.app')
@section('title')
    User
@endsection
@section('main')
    <div class="container p-4">
        <form action="{{ route('user') }}" method="POST" class="needs-validation card  mx-auto" id="caseForm" novalidate>
            @csrf
            <div class="card-header text-bg-primary">
                <h5 class="mb-0">User</h5>
            </div>
            <div class="container card-body">
                <div class="row mb-4">
                    <div class="col-5">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            autocomplete="off" value="{{ old('name') }}" required>
                        <div class="invalid-feedback ">
                            @if ($errors->has('name'))
                                @error('name')
                                    {{ $message }}
                                @enderror
                            @else
                                Name is Required.
                            @endif
                        </div>
                    </div>

                    {{-- <div class="col-4">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                            name="first_name" autocomplete="off" value="{{ old('first_name') }}" required>
                        <div class="invalid-feedback ">
                            @if ($errors->has('first_name'))
                                @error('first_name')
                                    {{ $message }}
                                @enderror
                            @else
                                First Name is Required.
                            @endif
                        </div>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                            autocomplete="off" value="{{ old('last_name') }}" required>
                        <div class="invalid-feedback ">
                            @if ($errors->has('last_name'))
                                @error('last_name')
                                    {{ $message }}
                                @enderror
                            @else
                                Last Name is Required.
                            @endif
                        </div>
                    </div> --}}
                    <div class="col-2">
                        <label class="form-label">Suffix</label>
                        <input type="text" class="form-control @error('suffix') is-invalid @enderror" name="suffix"
                            autocomplete="off" value="{{ old('suffix') }}">
                        <div class="invalid-feedback ">
                            @if ($errors->has('suffix'))
                                @error('suffix')
                                    {{ $message }}
                                @enderror
                            @endif
                        </div>
                    </div>
                    <div class="col-5">
                        <label class="form-label">Job Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('role') is-invalid @enderror" name="role"
                            autocomplete="off" value="{{ old('role') }}" required>
                        <div class="invalid-feedback ">
                            @if ($errors->has('role'))
                                @error('role')
                                    {{ $message }}
                                @enderror
                            @endif
                        </div>
                    </div>

                </div>
                <div class="row mb-4">
                    <div class="col-4">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" name="username"
                            autocomplete="off" value="{{ old('username') }}" required>
                        <div class="invalid-feedback ">
                            @if ($errors->has('username'))
                                @error('username')
                                    {{ $message }}
                                @enderror
                            @else
                                Username is Required.
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    {{-- <div class="col-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            autocomplete="off" value="{{ old('email') }}">
                        <div class="invalid-feedback ">
                            @if ($errors->has('email'))
                                @error('email')
                                    {{ $message }}
                                @enderror
                            @else
                                Valid Email is Required.
                            @endif

                        </div>
                    </div> --}}
                    {{-- <div class="col-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" name="username"
                            autocomplete="off" value="{{ old('username') }}">
                        <div class="invalid-feedback ">
                            @if ($errors->has('username'))
                                @error('username')
                                    {{ $message }}
                                @enderror
                            @else
                                Username is Required.
                            @endif
                        </div>
                    </div> --}}
                </div>
                <div class="row mb-4">
                    <div class="col-4">
                        <x-contact.create />
                    </div>
                    <div class="col-8">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control  @error('address') is-invalid @enderror" autocomplete="off">{{ old('address') }}</textarea>
                    </div>
                </div>
                {{-- @php
                    $entities = [
                        'Cases' => ['View', 'Create', 'Update', 'Delete'],
                        'Cases' => ['View', 'Create', 'Update', 'Delete'],
                        'Case Deposits' => ['View', 'Create', 'Update', 'Delete'],
                        'Case Deposits' => ['View', 'Create', 'Update', 'Delete'],
                        'Case Expenses' => ['View', 'Create', 'Update', 'Delete'],
                        'Case Hearings' => ['View', 'Create', 'Update', 'Delete'],
                        'Case Billings' => ['View', 'Create', 'Update', 'Delete'],
                        'Case Billing Deposits' => ['View', 'Create', 'Update', 'Delete'],
                    ];
                @endphp --}}
                {{-- 
                <div class="d-inline-block">
                    <div class="d-flex justify-content-between align-items-center pb-2">
                        <label class="form-label mb-0">Permissions</label>
                        <div>
                            <div class="btn btn-sm btn-success me-2" id="checkAllBtn">
                                Check All
                            </div>
                            <div class="btn btn-sm btn-danger" id="uncheckAllBtn">
                                Uncheck All
                            </div>
                        </div>
                    </div>
                    <div id="permissionsCon">
                        @foreach (config('enums.permissionModels', []) as $displayName => $model)
                            <div class="d-flex mb-2">
                                @foreach ($model['permissions'] as $permission)
                                    <input type="checkbox" class="btn-check btn-check-permission"
                                        name="permissions[{{ $displayName }}][{{ $permission }}]"
                                        id="btn-check-{{ $permission }}-{{ $displayName }}" autocomplete="off">
                                    <label class="btn-check-permission-label"
                                        for="btn-check-{{ $permission }}-{{ $displayName }}">
                                        <span class="me-2"> {!! permissionIcon($permission) !!} </span>
                                    </label>
                                @endforeach
                                <span class="ps-4">
                                    {{ $displayName }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div> --}}
                {{-- <div id="permissionsCon">
                    <div class="row mb-4">
                        @foreach ($entities as $entity => $actions)
                            <div class="col">
                                <div class="list-group">
                                    <div class="list-group-item active" aria-current="true">{{ $entity }}</div>
                                    @foreach ($actions as $action)
                                        <div class="list-group-item">
                                            <div class="form-check form-switch">
                                                <label class="form-check-label w-100">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="permissions[{{ $entity }}][{{ $action }}]">
                                                    {{ $action }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row mb-4">
                        <div class="col">
                            <div class="list-group">
                                <div class="list-group-item list-group-item-primary" aria-current="true">Clients</div>
                                <div class="list-group-item">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label w-100">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[Clients][View]">
                                            View
                                        </label>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label w-100">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[Clients][Create]">
                                            Create
                                        </label>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label w-100">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[Clients][Update]">
                                            Update
                                        </label>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label w-100">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[Clients][Delete]">
                                            Delete
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="list-group">
                                <div class="list-group-item list-group-item-info" aria-current="true">Associates</div>
                                <div class="list-group-item">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label w-100">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[Associates][View]">
                                            View
                                        </label>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label w-100">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[Associates][Create]">
                                            Create
                                        </label>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label w-100">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[Associates][Update]">
                                            Update
                                        </label>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label w-100">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[Associates][Delete]">
                                            Delete
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="list-group">
                                <div class="list-group-item list-group-item-warning" aria-current="true">Office Expense
                                </div>
                                <div class="list-group-item">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label w-100">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[Office Expenses][View]">
                                            View
                                        </label>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label w-100">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[Office Expenses][Create]">
                                            Create
                                        </label>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label w-100">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[Office Expenses][Update]">
                                            Update
                                        </label>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label w-100">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[Office Expenses][Delete]">
                                            Delete
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="list-group">
                                <div class="list-group-item list-group-item-secondary" aria-current="true">Custom Events
                                </div>
                                <div class="list-group-item">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label w-100">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[Custom Events][View]">
                                            View
                                        </label>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label w-100">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[Custom Events][Create]">
                                            Create
                                        </label>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label w-100">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[Custom Events][Update]">
                                            Update
                                        </label>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label w-100">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="permissions[Custom Events][Delete]">
                                            Delete
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="row mb-4">
                    <div class="col-12">
                        <label class="form-label">Permissions</label>
                        <div class="row">
                            <div class="col">
                                <div class="list-group">
                                    <div class="list-group-item active" aria-current="true">Cases</div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch" >
                                                View
                                            </label>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                Create
                                            </label>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                Update
                                            </label>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                Delete
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="list-group">
                                    <div class="list-group-item active" aria-current="true">Case Deposits</div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                View
                                            </label>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                Create
                                            </label>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                Update
                                            </label>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                Delete
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="list-group">
                                    <div class="list-group-item active" aria-current="true">Case Expenses</div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                View
                                            </label>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                Create
                                            </label>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                Update
                                            </label>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                Delete
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="list-group">
                                    <div class="list-group-item active" aria-current="true">Case Hearings</div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                View
                                            </label>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                Create
                                            </label>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                Update
                                            </label>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                Delete
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="list-group">
                                    <div class="list-group-item active" aria-current="true">Case Billings</div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                View
                                            </label>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                Create
                                            </label>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                Update
                                            </label>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                Delete
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="list-group">
                                    <div class="list-group-item active" aria-current="true">Case Billings Deposit</div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                View
                                            </label>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                Create
                                            </label>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                Update
                                            </label>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label w-100">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                                Delete
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    <script>
        $(document).on('click', '#checkAllBtn', function(e) {
            $("#permissionsCon .btn-check-permission").prop('checked', true)
        })
        $(document).on('click', '#uncheckAllBtn', function(e) {
            $("#permissionsCon .btn-check-permission").prop('checked', false)
        })
    </script>
@endsection

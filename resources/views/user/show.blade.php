@extends('layout.app')
@section('title')
    {{ $user->first_name }} {{ $user->last_name }} {{ $user->suffix }}
@endsection
@section('main')
    <div class="container my-5">
        <!-- user Overview -->
        <div class="card mb-4">
            <form id="editUserForm" class="m-0">
                @csrf
                <div class="card-header bg-light  text-white d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-bg-light d-inline me-2">
                            User:
                            <span class="editable-input">
                                <span class="value" data-name="name" data-value="{{ $user->name }}" data-class="w-init">
                                    {{ $user->name }}
                                </span>
                            </span>

                        </h5>
                    </div>
                    <div>
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm" data-bs-toggle="dropdown"
                                aria-expanded="false"></button>
                            <ul class="dropdown-menu shadow">
                                <li>
                                    <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                        <span class="text-muted me-2">#{{ $user->id }}</span>
                                    </h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <button class="dropdown-item" id="editUserBtn">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </button>
                                </li>
                                @if (auth()->user()->id === 1 && $user->id !== 1)
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                            data-bs-target="#deleteUserModal" data-user-id="{{ $user->id }}"
                                            data-modal-title="{{ $user->name }}">
                                            <i class="bi bi-trash me-2"></i>Delete
                                        </a>

                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-4">
                            <h6 class="text-primary fw-semibold">Username</h6>
                            @if (auth()->user()->id === 1 && $user->id !== 1)
                                <span class="editable-input">
                                    <span class="value" data-name="username" data-value="{{ $user->username }}">
                                        {{ $user->username }}
                                    </span>
                                </span>
                            @else
                                <span>
                                    {{ $user->username }}
                                </span>
                            @endif
                        </div>
                        <div class="col-4">
                            <h6 class="text-primary fw-semibold">Job Title</h6>
                            @if (auth()->user()->id === 1)
                                <span class="editable-input">
                                    <span class="value" data-name="role" data-value="{{ $user->role }}">
                                        {{ $user->role }}
                                    </span>
                                </span>
                            @else
                                <span>
                                    {{ $user->role }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-4 password-con visually-hidden">
                        <div class="col-4 ">
                            <h6 class="text-primary fw-semibold d-flex justify-content-between">
                                <span>Password</span>
                                <button type="button" class="btn btn-light" id="editPasswordBtn" data-bs-toggle="collapse"
                                    data-bs-target="#editPasswordCon" aria-expanded="false" aria-controls="editPasswordCon">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </h6>
                            <div class="border p-2 collapse" id="editPasswordCon">
                                <div class="border-bottom pb-2">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" class="form-control mb-2 " name="current_password" disabled>
                                </div>
                                <div class="pt-2">
                                    <label class="form-label">New Password</label>
                                    <input type="password" class="form-control mb-2" name="password" disabled>
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" name="password_confirmation" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-4">
                            <x-contact.show :route="route('contact', ['user', $user->id])" />
                        </div>
                        <div class="col-8">
                            <h6 class="text-primary fw-semibold">Address</h6>
                            <p>
                                @if ($user->address)
                                    <span class="editable-text-area">
                                        <span class="value" data-name="address" data-value="{{ $user->address }}">
                                            {{ $user->address }}</span>
                                    </span>
                                @else
                                    <span class="editable-text-area">
                                        <span class="value text-muted" data-name="address" data-value="">
                                            No address found</span>
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                    {{-- <div class="d-inline-block">
                        @can('create', $user)
                            <h6 class="text-primary fw-semibold d-flex justify-content-between align-items-center">
                                <span>Permissions</span>
                                <span class="edit-permissions-con visually-hidden">
                                    <div>
                                        <div class="btn btn-sm btn-success me-2" id="checkAllBtn">
                                            Check All
                                        </div>
                                        <div class="btn btn-sm btn-danger" id="uncheckAllBtn">
                                            Uncheck All
                                        </div>
                                    </div>
                                </span>
                            </h6>
                            <div id="permissionsCon">
                                @foreach (config('enums.permissionModels', []) as $displayName => $model)
                                    <div class="d-flex mb-2">
                                        @foreach ($model['permissions'] as $permission)
                                            <input type="checkbox" class="btn-check btn-check-permission"
                                                id="btn-check-{{ $permission }}-{{ $user->id }}-{{ $displayName }}"
                                                name="permissions[{{ $displayName }}][{{ $permission }}]"
                                                autocomplete="off" @if ($user->hasPermissionFor($displayName, $permission)) checked @endif disabled>
                                            <label class="btn-check-permission-label"
                                                for="btn-check-{{ $permission }}-{{ $user->id }}-{{ $displayName }}">
                                                <span class="me-2"> {!! permissionIcon($permission) !!} </span>
                                            </label>
                                        @endforeach
                                        <span class="ps-4">
                                            {{ $displayName }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endCan
                    </div> --}}

                    <div class="d-flex justify-content-end m-4 visually-hidden" id="submitBtnCon">
                        <div>
                            <button type="button" class="btn btn-secondary" id="cancelEditBtn">Cancel</button>
                            <button type="submit" class="btn btn-primary" form="editUserForm">Update
                                User</button>
                        </div>

                    </div>

                </div>
            </form>
            <div id="blurCon">
                <div class="mt-4 col">
                    <ul class="nav nav-tabs  ps-2" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " href="#notes" id="noteTab" data-bs-toggle="tab"
                                data-bs-target="#notesContent" type="button" role="tab"
                                aria-controls="notesContent" aria-selected="false">
                                <span class="pe-2">Notes</span>
                                <span class="badge rounded-pill bg-primary"
                                    id="user{{ $user->id }}NotesCount">{{ $user->notes_count }}</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " href="#activities" data-bs-toggle="tab"
                                data-bs-target="#activitiesContent" type="button" role="tab"
                                aria-controls="notesContent" aria-selected="false">
                                <span class="pe-2">Activities</span>
                            </a>
                        </li>
                    </ul>
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane " id="notesContent" role="tabpanel" aria-labelledby="notesTab">
                                    <div class="d-flex justify-content-end align-items-start mb-3">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addNoteModal"
                                            data-callback="window.fetchAjaxTable('notes', '#notesTable' , 1, false);
                                            const container =$('#note{{ $user->id }}NotesCount');
                                            const notesCount = Number(container.text()) + 1;
                                            container.text(notesCount);
                                            "
                                            data-route="{{ route('note', ['user', $user->id]) }}">
                                            Add Note
                                        </button>
                                    </div>
                                    <div id="notesTable"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane " id="activitiesContent" role="tabpanel"
                                    aria-labelledby="notesTab">
                                    <div id="activitiesTable"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-contact.add-contact-modal :route="route('contact', ['user', $user->id])" />
    <x-contact.edit-contact-modal :route="route('contact', ['user', $user->id])" />
    <x-contact.delete-contact-modal :route="route('contact', ['user', $user->id])" />
    @if (auth()->user()->id === 1)
        <x-user.delete-user-modal />
    @endif
    <script>
        $(document).on('show.bs.collapse', '#editPasswordCon', function(e) {
            $(this).find('input').attr('disabled', false)
        })
        $(document).on('hide.bs.collapse', '#editPasswordCon', function(e) {
            $(this).find('input').attr('disabled', true)
        })
        $(document).on('click', '#checkAllBtn', function(e) {
            $("#permissionsCon .btn-check-permission").prop('checked', true)
        })
        $(document).on('click', '#uncheckAllBtn', function(e) {
            $("#permissionsCon .btn-check-permission").prop('checked', false)
        })
        $(document).on('click', '#editUserBtn', function(e) {
            $("#permissionsCon .btn-check-permission").attr('disabled', false)
            $(this).attr('disabled', true)
            $('#submitBtnCon').removeClass('visually-hidden')
            $('.edit-permissions-con').removeClass('visually-hidden')
            $('.password-con').removeClass('visually-hidden')
            $('#blurCon').addClass('blur')
            $('.editable-input span.value').each(function() {
                $span = $(this);
                currentValue = $span.data('value');
                name = $span.data('name') || 'editableField';
                className = $span.data('class') || '';
                $span.hide();
                $input = window.editableInput(currentValue, name, className).insertAfter($span);
            })
            $('.editable-text-area span.value').each(function() {
                $span = $(this);
                currentValue = $span.data('value');
                name = $span.data('name') || 'editableField';
                $span.hide();
                $textarea = window.editableTextArea(currentValue, name).insertAfter($span);
            })
        })

        function cancelEdit(success = false) {
            $("#permissionsCon .btn-check-permission").attr('disabled', true)
            $('#editUserBtn').attr('disabled', false)
            $('#submitBtnCon').addClass('visually-hidden')
            $('.edit-permissions-con').addClass('visually-hidden')
            $("#editPasswordCon").collapse('hide');
            $('.password-con').addClass('visually-hidden')
            $('#blurCon').removeClass('blur')

            $('.editable-input span.value').each(function() {
                $span = $(this);
                if (success) {
                    newValue = $span.next('input.edit').val();
                    $span.data('value', newValue);
                    $span.text(newValue)
                }
                $span.show();
                $span.next('input.edit').remove()
            })
            $('.editable-text-area span.value').each(function() {
                $span = $(this);
                if (success) {
                    newValue = $span.next('textarea.edit').val();
                    $span.data('value', newValue);
                    $span.text(newValue)
                }
                $span.show();
                $span.next('textarea.edit').remove()
            })
        }
        $(document).on('click', '#cancelEditBtn', function(e) {
            cancelEdit()
        })


        $(document).on('submit', '#editUserForm', function(e) {
            e.preventDefault();
            var $submitBtn = $(this).find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('user.update', $user->id) }}',
                type: 'PUT',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        cancelEdit(true);
                        window.showToast('Success', response.message, 'success');
                        $('#editPasswordCon').find('input').val('');
                    }
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                },
                error: function(xhr) {
                    window.toastError(xhr);
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                }
            });
        })
        $(document).ready(function() {
            @if (request()->get('edit'))
                $('#editUserBtn').click()
            @endif
            @if (request()->get('edit_password'))
                $('#editPasswordBtn').click()
            @endif
            var hash = window.location.hash;

            if (hash) {
                $('a[href="' + hash + '"][role="tab"]').tab('show');

                //  console.log($('a[href="' + hash + '"][role="tab"]'))
            }
            $('a[data-bs-toggle="tab"]').on('click', function() {
                var tabId = $(this).attr('href');
                history.pushState(null, null, tabId);
            });

            $(document).on('click', '#notesTable  .pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                window.fetchAjaxTable('notes', '#notesTable', page, false);
            });

            function loadTables() {
                window.fetchAjaxTable('notes', '#notesTable', 1)
                window.fetchAjaxTable('activities', '#activitiesTable', 1)
            }
            loadTables()
        });
    </script>
@endsection

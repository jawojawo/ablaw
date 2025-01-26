@extends('layout.app')
@section('title')
    Users
@endsection
@section('main')
    <div class="container h-100 py-4">
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <table class="table table-bordered">
            <caption class="caption-top">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex">
                        <h2 class="me-2">Users</h2>
                    </div>
                    <a href="{{ route('user.create') }}" class="btn btn-success ">Create New User</a>
                </div>
            </caption>
            {{-- 
        <caption class="caption-top">
            <div class="d-flex justify-content-between align-items-end mb-3">
                <div>
                    @if (request()->get('first_name') || request()->get('last_name'))
                        <a href="{{ route('user') }}" class="btn btn-link text-danger p-0">
                            Clear Search
                        </a>
                    @endif
                </div>
                <div class=" w-50 ">
                    <form class="mb-0">
                        <div class="input-group dropstart">
                            <input type="search" class="form-control" placeholder="First Name" name="first_name"
                                value="{{ request()->get('first_name') }}">
                            <input type="search" class="form-control" placeholder="Last Name" name="last_name"
                                value="{{ request()->get('last_name') }}">
                            <button class="btn btn-primary" type="submit" aria-expanded="false"><i
                                    class="bi bi-search"></i> Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </caption> --}}

            @if ($users->isEmpty())
                <caption class="caption-top">
                    <div class="text-center fs-3 card p-5">No Users Found</div>
                </caption>
            @else
                <thead>
                    <tr>
                        <th class="table-td-min text-center">#</th>
                        <th class="w-100">Name</th>
                        <th class="">Role</th>
                        <th>Username</th>

                        <th>Password</th>

                        {{-- <th>Email</th> --}}
                        <th class="text-center">Address</th>
                        <th class="text-center"><i class="bi bi-phone"></i></th>
                        <th class="text-center"><i class="bi bi-journals"></i></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                <a class="text-reset text-center" href="{{ route('user.show', $user->id) }}">
                                    <div>{{ $user->id }}</div>
                                </a>
                            </td>
                            <td>{{ $user->name }}</td>
                            <td class="text-nowrap">{{ $user->role }}</td>
                            <td>{{ $user->username }}</td>
                            <td>

                                @if ($user->initial_password)
                                    <div class="input-group input-group-sm" style="width:150px">

                                        <input class="form-control border-secondary user-password-inp" type="password"
                                            value="{{ $user->initial_password }}" readonly>
                                        <button class="btn btn-outline-secondary user-password-btn"
                                            data-id="{{ $user->id }}" type="button">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                    </div>
                                @else
                                    <span class="text-muted">Changed</span>
                                @endif
                            </td>
                            {{-- <td>{{ $user->email }}</td> --}}

                            <td>
                                @if ($user->address)
                                    <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-reset"
                                        data-bs-toggle="popover" data-bs-title="<div class='text-center'>Address</div>"
                                        data-bs-content="
                                <div class='text-center'>
                                    {{ $user->address }}
                                </div>
                                ">
                                        <div class="px-2"
                                            style="max-width: 300px;text-overflow: ellipsis;white-space:nowrap;overflow: hidden;">
                                            {{ $user->address }}
                                        </div>
                                    </a>
                                @else
                                    <span class="text-muted px-2">None</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($user->contacts_count > 0)
                                    <button class="btn-primary btn p-0 px-2" data-bs-toggle="modal"
                                        data-bs-target="#showContactsModal" data-contacts='{{ $user->contacts }}'>
                                        {{ $user->contacts_count }}
                                    </button>
                                @else
                                    <span class="text-muted">None</span>
                                @endif
                            </td>
                            <td class="text-center">

                                <button class="btn-outline-dark btn p-0 px-2" id="user{{ $user->id }}NotesCount"
                                    data-bs-toggle="modal" data-bs-target="#showNotesModal"
                                    data-route='{{ route('note', ['user', $user->id]) }}'>
                                    {{ $user->notes_count }}
                                </button>

                            </td>

                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm" data-bs-toggle="dropdown"
                                        aria-expanded="false"></button>
                                    <ul class="dropdown-menu shadow">
                                        <li>
                                            <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                                <span class="text-muted me-2">#{{ $user->id }}</span>
                                                <span>{{ $user->full_name }}</span>
                                            </h6>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('user.show', $user->id) }}">
                                                <i class="bi bi-eye me-2"></i>View
                                            </a>
                                        </li>
                                        <li class="text-nowrap">
                                            <form action="{{ route('user.resetPassword', $user->id) }}" method="POST"
                                                class="mb-0">
                                                @csrf
                                                <button type="submit" class="btn">

                                                    <i class="bi bi-unlock me-2"></i>Reset Password
                                                </button>
                                            </form>

                                        </li>

                                        <li>
                                            <a class="dropdown-item" href="{{ route('user.show', $user->id) }}?edit=true">
                                                <i class="bi bi-pencil me-2"></i>Edit
                                            </a>
                                        </li>
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
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach


                </tbody>
            @endif
        </table>

        {{ $users->appends(request()->query())->links() }}
    </div>
    @if (auth()->user()->id === 1)
        <x-user.delete-user-modal />
    @endif
    <script>
        $(document).on('click', '.user-password-btn', function(e) {
            id = $(this).data('id');
            if ($(this).parent().find('.user-password-inp').attr('type') == 'text') {
                $(this).parent().find('.user-password-inp').attr('type', 'password');
                $(this).html('<i class="bi bi-eye"></i>');
                return;
            }
            $(this).parent().find('.user-password-inp').attr('type', 'text');
            $(this).html('<i class="bi bi-eye-slash"></i>');

        })
        $(document).on('focus', '.user-password-inp', function(e) {
            if ($(this).attr('type') != 'password') {
                $(this).select();
            }

        })
    </script>
@endsection

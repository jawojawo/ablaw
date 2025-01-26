@extends('layout.app')
@section('title')
    Associates
@endsection
@section('main')
    <div class="container h-100 py-4">


        <table class="table table-bordered">
            <caption class="caption-top">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex">
                        <h2 class="me-2">Associates</h2>
                    </div>
                    <a href="{{ route('associate.create') }}" class="btn btn-success ">Create New Associate</a>
                </div>
            </caption>

            <caption class="caption-top">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        @if (request()->get('name'))
                            <a href="{{ route('associate') }}" class="btn btn-link text-danger p-0">
                                Clear Search
                            </a>
                        @endif
                    </div>
                    <div class=" w-50 ">
                        <form class="mb-0">
                            <div class="input-group dropstart">
                                <input type="search" class="form-control" placeholder="Associate's Name" name="name"
                                    value="{{ request()->get('name') }}">
                                <button class="btn btn-primary" type="submit" aria-expanded="false"><i
                                        class="bi bi-search"></i> Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </caption>

            @if ($associates->isEmpty())
                <caption class="caption-top">
                    <div class="text-center fs-3 card p-5">No Associates Found</div>
                </caption>
            @else
                <thead>
                    <tr>
                        <th class="table-td-min text-center">#</th>
                        <th class="w-100">Name</th>
                        <th class="text-center">Address</th>

                        <th class="text-center">Cases</th>
                        <th class="text-center"><i class="bi bi-phone"></i></th>
                        <th class="text-center"><i class="bi bi-journals"></i></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($associates as $associate)
                        <tr>
                            <td><a class="text-reset text-center" href="{{ route('associate.show', $associate->id) }}">
                                    <div>{{ $associate->id }}</div>
                                </a>
                            </td>
                            <td>{{ $associate->name }}</td>
                            <td>
                                @if ($associate->address)
                                    <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-reset"
                                        data-bs-toggle="popover" data-bs-title="<div class='text-center'>Address</div>"
                                        data-bs-content="
                                <div class='text-center'>
                                    {{ $associate->address }}
                                </div>
                                ">
                                        <div class="px-2"
                                            style="max-width: 300px;text-overflow: ellipsis;white-space:nowrap;overflow: hidden;">
                                            {{ $associate->address }}
                                        </div>
                                    </a>
                                @else
                                    <span class="text-muted px-2">None</span>
                                @endif
                            </td>

                            <td class="text-center">
                                {{ $associate->law_cases_count }}
                            </td>
                            <td class="text-center">
                                @if ($associate->contacts_count > 0)
                                    <button class="btn-primary btn p-0 px-2" data-bs-toggle="modal"
                                        data-bs-target="#showContactsModal" data-contacts='{{ $associate->contacts }}'>
                                        {{ $associate->contacts_count }}
                                    </button>
                                @else
                                    <span class="text-muted">None</span>
                                @endif
                            </td>
                            <td class="text-center">

                                <button class="btn-outline-dark btn p-0 px-2" id="associate{{ $associate->id }}NotesCount"
                                    data-bs-toggle="modal" data-bs-target="#showNotesModal"
                                    data-route='{{ route('note', ['associate', $associate->id]) }}'>
                                    {{ $associate->notes_count }}
                                </button>

                            </td>


                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm" data-bs-toggle="dropdown"
                                        aria-expanded="false"></button>
                                    <ul class="dropdown-menu shadow">
                                        <li>
                                            <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                                <span class="text-muted me-2">#{{ $associate->id }}</span>
                                                <span>{{ $associate->full_name }}</span>
                                            </h6>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('associate.show', $associate->id) }}">
                                                <i class="bi bi-eye me-2"></i>View
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('associate.show', $associate->id) }}?edit=true">
                                                <i class="bi bi-pencil me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                                data-bs-target="#deleteAssociateModal"
                                                data-associate-id="{{ $associate->id }}"
                                                data-modal-title="{{ $associate->name }}">
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

        {{ $associates->appends(request()->query())->links() }}
    </div>
    @can('delete', getPermissionClass('Associates'))
        <x-associate.delete-associate-modal />
    @endcan
@endsection

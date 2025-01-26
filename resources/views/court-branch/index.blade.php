@extends('layout.app')
@section('title')
    Court Branches
@endsection
@section('main')
    <div class="container h-100 py-4">


        <table class="table table-bordered">
            <caption class="caption-top">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex">
                        <h2 class="me-2">Court Branches</h2>
                    </div>
                    <a href="{{ route('courtBranch.create') }}" class="btn btn-success ">Create New Court Branch</a>
                </div>
            </caption>

            <caption class="caption-top">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        @if (request()->get('region') || request()->get('city') || request()->get('type') || request()->get('branch'))
                            <a href="{{ route('courtBranch') }}" class="btn btn-link text-danger p-0">
                                Clear Search
                            </a>
                        @endif
                    </div>
                    <div class=" w-75 ">
                        <form class="mb-0">
                            <div class="input-group dropstart">
                                <select class="form-select" name="region">
                                    <option value="">Region/Province</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->region }}"
                                            {{ request()->get('region') == $region->region ? 'selected' : '' }}>
                                            {{ $region->region }}</option>
                                    @endforeach
                                </select>
                                <input type="search" class="form-control" placeholder="City" name="city"
                                    value="{{ request()->get('city') }}">
                                <select class="form-select" name="type">
                                    <option value="">Court Type</option>
                                    @foreach ($courtTypes as $courtType)
                                        <option value="{{ $courtType->type }}"
                                            {{ request()->get('type') == $courtType->type ? 'selected' : '' }}>
                                            {{ $courtType->type }}</option>
                                    @endforeach
                                </select>
                                <input type="search" class="form-control" placeholder="Branch" name="branch"
                                    value="{{ request()->get('branch') }}">
                                <button class="btn btn-primary" type="submit" aria-expanded="false"><i
                                        class="bi bi-search"></i> Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </caption>

            @if ($courtBranches->isEmpty())
                <caption class="caption-top">
                    <div class="text-center fs-3 card p-5">No Court Branches Found</div>
                </caption>
            @else
                <thead>
                    <tr>
                        <th class="table-td-min text-center">#</th>
                        <th>Region/Province</th>
                        <th>City/Municipality</th>
                        <th>Type</th>
                        <th class="table-td-min">Branch</th>
                        <th class="text-center">Address</th>
                        <th>Judge</th>
                        <th class="table-td-min">
                            # Hearing
                        </th>
                        <th class="text-center"><i class="bi bi-phone"></i></th>
                        <th class="text-center"><i class="bi bi-journals"></i></th>
                        <th class="table-td-min"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courtBranches as $courtBranch)
                        <tr>
                            <td><a class="text-reset text-center" href="{{ route('courtBranch.show', $courtBranch->id) }}">
                                    <div>{{ $courtBranch->id }}</div>
                                </a>
                            </td>
                            <td>{{ $courtBranch->region }}</td>
                            <td>{{ $courtBranch->city }}</td>
                            <td class="text-center">
                                <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-reset"
                                    data-bs-toggle="popover"
                                    data-bs-content="<div class='text-center'>{{ $courtBranch->type }}</div>">


                                    {{ abbreviate($courtBranch->type) }}
                                </a>

                            </td>
                            <td class="text-center">{{ $courtBranch->branch }}</td>
                            <td>
                                @if ($courtBranch->address)
                                    <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-reset"
                                        data-bs-toggle="popover" data-bs-title="<div class='text-center'>Address</div>"
                                        data-bs-content="
                                <div class='text-center'>
                                    {{ $courtBranch->address }}
                                </div>
                                ">
                                        <div
                                            style="max-width: 300px;text-overflow: ellipsis;white-space:nowrap;overflow: hidden;">
                                            {{ $courtBranch->address }}
                                        </div>
                                    </a>
                                @else
                                    <span class="text-muted ">None</span>
                                @endif
                            </td>
                            <td>{{ $courtBranch->judge }}</td>
                            <td class="text-center">{{ $courtBranch->hearings_count }}</td>
                            <td class="text-center">
                                @if ($courtBranch->contacts_count > 0)
                                    <button class="btn-primary btn p-0 px-2" data-bs-toggle="modal"
                                        data-bs-target="#showContactsModal" data-contacts='{{ $courtBranch->contacts }}'>
                                        {{ $courtBranch->contacts_count }}
                                    </button>
                                @else
                                    <span class="text-muted">None</span>
                                @endif
                            </td>
                            <td class="text-center">

                                <button class="btn-outline-dark btn p-0 px-2"
                                    id="courtBranch{{ $courtBranch->id }}NotesCount" data-bs-toggle="modal"
                                    data-bs-target="#showNotesModal"
                                    data-route='{{ route('note', ['courtBranch', $courtBranch->id]) }}'>
                                    {{ $courtBranch->notes_count }}
                                </button>

                            </td>


                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm" data-bs-toggle="dropdown"
                                        aria-expanded="false"></button>
                                    <ul class="dropdown-menu shadow">
                                        <li>
                                            <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                                <span class="text-muted me-2">#{{ $courtBranch->id }}</span>
                                            </h6>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('courtBranch.show', $courtBranch->id) }}">
                                                <i class="bi bi-eye me-2"></i>View
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('courtBranch.show', $courtBranch->id) }}?edit=true">
                                                <i class="bi bi-pencil me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                                data-bs-target="#deleteCourtBranchModal"
                                                data-court-branch-id="{{ $courtBranch->id }}"
                                                data-modal-title="{{ $courtBranch->region }} - {{ $courtBranch->city }} - {{ $courtBranch->branch }}">
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

        {{ $courtBranches->appends(request()->query())->links() }}
    </div>

    <x-court-branch.delete-court-branch-modal />

@endsection

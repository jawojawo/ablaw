@props(['hearings'])
@php
    $paginatedHearings = $hearings
        ->with(['user', 'courtBranch'])
        ->orderedHearings()
        ->paginate(10, ['*'], 'hearings-page');
@endphp
@if ($paginatedHearings->isEmpty())
    <p class="text-center text-muted">No Hearings found.</p>
@else
    <table class="table table-bordered table-center">
        <thead>
            <tr>
                <th class="table-td-min">#</th>
                <th>Title</th>
                <th>Court</th>
                <th>
                    Hearing Date
                    </a>
                </th>
                <th>By</th>
                <th class="table-td-min"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($paginatedHearings as $hearing)
                <tr class="{{ \Carbon\Carbon::parse($hearing->hearing_date)->isPast() ? 'table-secondary' : '' }}">
                    <td class="text-nowrap">{{ $hearing->id }}</td>
                    <td>{{ $hearing->title }}</td>
                    <td style="white-space: nowrap">{{ $hearing->courtBranch->formatted_court }}</td>
                    <td style="white-space: nowrap">
                        <div class="text-nowrap">{{ $hearing->formatted_hearing_date['date'] }}
                        </div>
                        <div class="text-muted">{{ $hearing->formatted_hearing_date['time'] }}
                        </div>

                    </td>
                    <td>{{ $hearing->user->name }}</td>
                    <td>
                        <div class="dropdown">

                            <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm" data-bs-toggle="dropdown"
                                aria-expanded="false"></button>
                            <ul class="dropdown-menu shadow">
                                <li>
                                    <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                        <span class="text-muted">#{{ $hearing->id }}</span>
                                    </h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" data-hearing-id="{{ $hearing->id }}"
                                        data-hearing-title="{{ $hearing->title }}"
                                        data-hearing-court-branch='{{ $hearing->courtBranch }}'
                                        data-hearing-date="{{ $hearing->hearing_date }}" data-bs-toggle="modal"
                                        data-bs-target="#editHearingModal">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#"
                                        data-hearing-id="{{ $hearing->id }}" data-bs-toggle="modal"
                                        data-bs-target="#deleteHearingModal">
                                        <i class="bi bi-trash me-2"></i>Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $paginatedHearings->links() }}

@endif

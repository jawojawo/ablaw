@props(['hearings', 'multiCase' => false, 'noEdit' => false, 'pdf' => false, 'addHearings' => true])
@php
    if ($pdf) {
        $paginatedHearings = $hearings
            ->withCount(['notes'])
            ->with(['lawCase', 'courtBranch'])
            ->orderedHearings()
            ->get();
    } else {
        $paginatedHearings = $hearings
            ->withCount(['notes'])
            ->with(['lawCase', 'courtBranch'])
            ->orderedHearings()
            ->paginate(10, ['*'], 'hearings-page');
    }
@endphp
@if ($paginatedHearings->isEmpty())
    <p class="text-center text-muted">No Hearings found.</p>
@else
    <table class="table table-bordered ">
        @if (!$pdf)
            <caption class="caption-top">
                <div class="d-flex">

                    <div class="d-flex
                            me-4">
                        <div class="border border-secondary bg-primary pe-4 me-2"></div>
                        <span>Ongoing</span>
                    </div>
                    <div class="d-flex me-4">
                        <div class="border border-secondary pe-4 me-2"></div>
                        <span>Upcoming</span>
                    </div>
                    <div class="d-flex me-4">
                        <div class="border border-secondary bg-danger pe-4 me-2"></div>
                        <span>Canceled</span>
                    </div>
                    <div class="d-flex me-4">
                        <div class="border border-secondary bg-secondary pe-4 me-2"></div>
                        <span>Completed</span>
                    </div>
                </div>
            </caption>
        @endif
        <thead>
            <tr class="align-middle">
                <th class="table-td-min text-center">#</th>
                <th>Title</th>
                <th>Court</th>
                <th>Date</th>
                <th class="table-td-min text-center"><i class="bi bi-info-square"></i></th>
                @if (!$pdf)
                    <th class="table-td-min text-center"><i class="bi bi-journals"></i></th>
                    @if (!$noEdit)
                        <th class="table-td-min"></th>
                    @endif
                @endif
            </tr>
        </thead>
        <tbody>
            @php
                $previousLawCaseNumber = null;
            @endphp
            @foreach ($paginatedHearings as $hearing)
                @if ($multiCase)
                    @if ($previousLawCaseNumber !== $hearing->lawCase->case_number)
                        <tr>

                            <td class="text-center table-info border-end-0">
                                <a class="text-reset text-center"
                                    href="{{ route('case.show', $hearing->lawCase->id) }}">
                                    <div>{{ $hearing->lawCase->id }}</div>
                                </a>
                            </td>

                            <td colspan="100" class="table-info border-start-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold pe-3">{{ $hearing->lawCase->case_number }}</span>
                                    @if ($addHearings)
                                        <span class="fst-italic">{{ $hearing->lawCase->case_title }}
                                            <button class="btn btn-sm btn-primary ms-4"
                                                data-law-case-id={{ $hearing->lawCase->id }} data-bs-toggle="modal"
                                                data-bs-target="#addHearingModal">
                                                Add Hearing
                                            </button>
                                        </span>
                                    @endif
                                </div>
                            </td>

                        </tr>
                        @php
                            $previousLawCaseNumber = $hearing->lawCase->case_number;
                        @endphp
                    @endif
                @endif
                <tr>
                    {{-- <tr class="{{ \Carbon\Carbon::parse($hearing->hearing_date)->isPast() ? 'table-secondary' : '' }}"> --}}
                    <td class="text-center">{{ $hearing->id }}</td>
                    <td>{{ $hearing->title }}</td>
                    <td>{{ $hearing->courtBranch->formatted_court }}</td>
                    <td style="white-space: nowrap">
                        <div class="text-nowrap">
                            {{ formattedDate($hearing->hearing_date) }}

                        </div>
                        <div class="text-muted">
                            {{ formattedTime($hearing->hearing_date) }}
                        </div>

                    </td>
                    <td>
                        @if (!$pdf)
                            @switch($hearing->status)
                                @case('ongoing')
                                    <div class="border border-secondary bg-primary m-auto" style="width:20px;height:20px"></div>
                                @break

                                @case('completed')
                                    <div class="border border-secondary bg-secondary m-auto" style="width:20px;height:20px">
                                    </div>
                                @break

                                @case('canceled')
                                    <div class="border border-secondary bg-danger m-auto" style="width:20px;height:20px"></div>
                                @break

                                @default
                                    <div class="border border-secondary m-auto" style="width:20px;height:20px"></div>
                            @endswitch
                        @else
                            {{ $hearing->status }}
                        @endif
                    </td>
                    @if (!$pdf)
                        <td class="text-center">

                            <button class="btn-outline-dark btn p-0 px-2" id="hearing{{ $hearing->id }}NotesCount"
                                data-bs-toggle="modal" data-bs-target="#showNotesModal"
                                data-route='{{ route('note', ['hearing', $hearing->id]) }}'>
                                {{ $hearing->notes_count }}
                            </button>

                        </td>

                        @if (!$noEdit)
                            <td>
                                <div class="dropdown">

                                    <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm"
                                        data-bs-toggle="dropdown" aria-expanded="false"></button>
                                    <ul class="dropdown-menu shadow">
                                        <li>
                                            <h6
                                                class="dropdown-header d-flex justify-content-between align-items-center">
                                                <span class="text-muted">#{{ $hearing->id }}</span>
                                            </h6>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#"
                                                data-hearing-id="{{ $hearing->id }}"
                                                data-hearing-title="{{ $hearing->title }}"
                                                data-hearing-status="{{ $hearing->status }}"
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
                        @endif
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    @if (!$pdf)
        {{ $paginatedHearings->links() }}
    @endif
@endif

@extends('layout.app')
@section('title')
    Custom Events
@endsection
@section('main')
    <div class="container h-100 py-4">


        <table class="table table-bordered">
            <caption class="caption-top">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex">
                        <h2 class="me-2">Custom Events</h2>
                    </div>
                    <a href="{{ route('customEvent.create') }}" class="btn btn-success ">Create New Custom Events</a>
                </div>
            </caption>

            <caption class="caption-top">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        @if (request()->get('type') || request()->get('title') || request()->get('start_time') || request()->get('end_time'))
                            <a href="{{ route('customEvent') }}" class="btn btn-link text-danger p-0">
                                Clear Search
                            </a>
                        @endif
                    </div>
                    <div class=" w-75 ">
                        <form class="mb-0">
                            <div class="input-group dropstart">
                                <div class="form-control p-0 border-0">
                                    <input type="search" class="form-control custom-datalist-input"
                                        style="border-top-right-radius:0;border-bottom-right-radius:0" placeholder="Type"
                                        name="type" value="{{ request()->get('type') }}"
                                        data-datalist="{{ $types->toJson() }}">
                                </div>
                                <input type="search" class="form-control w-25" placeholder="Title" name="title"
                                    value="{{ request()->get('title') }}">
                                <button class="btn btn-outline-secondary dropdown-toggle dropstart z-3" type="button"
                                    data-bs-toggle="dropdown" data-bs-auto-close="false" aria-expanded="false">
                                    @if (request()->get('start_time') || request()->get('end_time'))
                                        <span
                                            class="red-toggle position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                                        </span>
                                    @endif
                                </button>
                                <div class="dropdown-menu p-0 " style="width:350px">
                                    <div class="card border-0">
                                        <div class="card-header text-bg-primary">
                                            Advance Search
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label d-block">Date</label>
                                                <div class="d-flex gap-2">
                                                    <div>
                                                        <input type="text"
                                                            class="form-control date-picker-default flatpickr-input"
                                                            placeholder="From" name="start_time"
                                                            value="{{ request('start_time') }}">
                                                    </div>
                                                    <div>
                                                        <input type="text"
                                                            class="form-control date-picker-default flatpickr-input"
                                                            placeholder="To" name="end_time"
                                                            value="{{ request('end_time') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit" aria-expanded="false"><i
                                        class="bi bi-search"></i> Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </caption>
            <caption class="caption-top">
                <div class="row mb-4">
                    <div class=" col-lg-12">
                        <div class="card">
                            <div class="card-header text-bg-primary">
                                <h6 class="mb-0">Custom Events Overview</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="text-secondary pb-2">
                                            Events from <span class="text-primary">{{ formattedDate($minDate) }}</span>
                                            to
                                            <span class="text-primary">{{ formattedDate($maxDate) }}</span>
                                        </div>
                                        <div class=" border-bottom">
                                            <div class="d-flex justify-content-between  my-2 ">
                                                <span>{{ $customEvents->total() }} Events</span>
                                                <span>
                                                    @if ($ongoingCount)
                                                        <span class="badge rounded-pill text-bg-primary  m-1">
                                                            <span class="badge text-bg-light">{{ $ongoingCount }}</span>
                                                            Ongoing
                                                        </span>
                                                    @endif
                                                    @if ($upcomingCount)
                                                        <span
                                                            class="badge rounded-pill text-bg-light btn btn-outline-dark m-1"
                                                            style="cursor:auto">
                                                            <span class="badge text-bg-primary">{{ $upcomingCount }}</span>
                                                            Upcoming
                                                        </span>
                                                    @endif
                                                    @if ($pastCount)
                                                        <span class="badge rounded-pill text-bg-secondary  m-1">
                                                            <span class="badge text-bg-light">{{ $pastCount }}</span>
                                                            Past Events
                                                        </span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </caption>
            @if ($customEvents->isEmpty())
                <caption class="caption-top">
                    <div class="text-center fs-3 card p-5">No Custom Event Found</div>
                </caption>
            @else
                <thead>
                    <tr>
                        <th class="table-td-min text-center">#</th>
                        <th>Title</th>
                        {{-- <th>Description</th> --}}
                        <th>Type</th>
                        <th class="text-center">
                            <div></div>Schedule
                            <div class="d-flex justify-content-between">
                                <div>From</div>
                                <div>To</div>
                            </div>
                        </th>
                        <th>Location</th>
                        <th class="text-center">
                            <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-init"
                                data-bs-toggle="popover" data-bs-title="Status"
                                data-bs-content="
                                    <div class='d-flex'>
                                        <div class='bg-primary' style='width:15px;height:15px'></div>
                                        <span class='ps-2'>Ongoing</span>
                                    </div>
                                     <div class='d-flex'>
                                        <div class='border border-dark ' style='width:15px;height:15px'></div>
                                        <span class='ps-2'>Upcoming</span>
                                    </div>
                                     <div class='d-flex'>
                                        <div class='bg-secondary' style='width:15px;height:15px'></div>
                                        <span class='ps-2'>Past</span>
                                    </div>
                                ">
                                <i class="bi bi-info-square"></i>
                            </a>
                        </th>
                        <th>By</th>
                        <th class="text-center"><i class="bi bi-journals"></i></th>
                        <th class="table-td-min"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customEvents as $customEvent)
                        <tr>
                            <td>
                                <a class="text-reset text-center" href="{{ route('customEvent.show', $customEvent->id) }}">
                                    <div>{{ $customEvent->id }}</div>
                                </a>
                            </td>
                            <td class="text-nowrap">
                                @if ($customEvent->description)
                                    <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-dark"
                                        data-bs-toggle="popover" data-bs-title="<div class='text-center'>Description</div>"
                                        data-bs-content="<div class='fst-italic'>{{ $customEvent->description }}</div>">
                                        {{ $customEvent->title }}
                                    </a>
                                @else
                                    {{ $customEvent->title }}
                                @endif

                            </td>
                            {{-- <td>{{ $customEvent->description }}</td> --}}
                            <td class="table-td-min">{{ $customEvent->type }}</td>
                            <td class="text-nowrap table-td-min p-0">
                                <div class="border-bottom t-info bg-gradient text-center">
                                    {{ formattedDateDiff($customEvent->start_time, $customEvent->end_time) }}</div>
                                <div class="d-flex justify-content-between align-items-center p-1">
                                    <div class="ps-1 pe-5">
                                        <div>{{ formattedDate($customEvent->start_time) }}</div>
                                        <div class="text-muted">{{ formattedTime($customEvent->start_time) }}</div>
                                    </div>

                                    <div class="pe-1">
                                        <div>{{ formattedDate($customEvent->end_time) }}</div>
                                        <div class="text-muted text-end">{{ formattedTime($customEvent->end_time) }}</div>
                                    </div>
                                </div>

                            </td>

                            <td class="table-td-min">
                                @if ($customEvent->location)
                                    <a tabindex="0" data-bs-custom-class="custom-popover"
                                        class="popover-click text-reset" data-bs-toggle="popover"
                                        data-bs-title="<div class='text-center'>Location</div>"
                                        data-bs-content="
                            <div class='text-center'>
                                {{ $customEvent->location }}
                            </div>
                            ">
                                        <div
                                            style="max-width: 300px;text-overflow: ellipsis;white-space:nowrap;overflow: hidden;">
                                            {{ $customEvent->location }}
                                        </div>
                                    </a>
                                @else
                                    <span class="text-muted">None</span>
                                @endif

                            </td>
                            <td class="table-td-min">{!! $customEvent->statusBadge !!}</td>
                            <td class="table-td-min">{{ $customEvent->user->name }}</td>
                            <td class="text-center table-td-min">
                                <button class="btn-outline-dark btn p-0 px-2"
                                    id="customEvent{{ $customEvent->id }}NotesCount" data-bs-toggle="modal"
                                    data-bs-target="#showNotesModal"
                                    data-route='{{ route('note', ['customEvent', $customEvent->id]) }}'>
                                    {{ $customEvent->notes_count }}
                                </button>
                            </td>

                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm"
                                        data-bs-toggle="dropdown" aria-expanded="false"></button>
                                    <ul class="dropdown-menu shadow">
                                        <li>
                                            <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                                <span class="text-muted me-2">#{{ $customEvent->id }}</span>
                                            </h6>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('customEvent.show', $customEvent->id) }}">
                                                <i class="bi bi-eye me-2"></i>View
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('customEvent.show', $customEvent->id) }}?edit=true">
                                                <i class="bi bi-pencil me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                                data-bs-target="#deleteCustomEventModal"
                                                data-custom-event-id="{{ $customEvent->id }}"
                                                data-modal-title="{{ $customEvent->type }}">
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

        {{ $customEvents->appends(request()->query())->links() }}
    </div>
    @can('delete', getPermissionClass('Custom Events'))
        <x-custom-event.delete-custom-event-modal />
    @endcan
@endsection

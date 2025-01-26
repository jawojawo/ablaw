@extends('layout.app')
@section('title')
    clients
@endsection
@section('main')
    <div class="container h-100 py-4">


        <table class="table table-bordered">
            <caption class="caption-top">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex">
                        <h2 class="me-2">Clients</h2>
                    </div>
                    <a href="{{ route('client.create') }}" class="btn btn-success ">Create New Client</a>
                </div>
            </caption>

            <caption class="caption-top">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        @if (request()->get('name'))
                            <a href="{{ route('client') }}" class="btn btn-link text-danger p-0">
                                Clear Search
                            </a>
                        @endif
                    </div>
                    <div class=" w-50 ">
                        <form class="mb-0">
                            <div class="input-group dropstart">
                                <input type="search" class="form-control" placeholder="Client's Name" name="name"
                                    value="{{ request()->get('name') }}">
                                <button class="btn btn-primary" type="submit" aria-expanded="false"><i
                                        class="bi bi-search"></i> Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </caption>

            @if ($clients->isEmpty())
                <caption class="caption-top">
                    <div class="text-center fs-3 card p-5">No Clients Found</div>
                </caption>
            @else
                <thead>
                    <tr>
                        <th class="table-td-min text-center">#</th>
                        <th class="w-100">Name</th>
                        <th class="text-center">Address</th>
                        <th class="text-center"><i class="bi bi-phone"></i></th>

                        <th>Deposits</th>
                        <th>Cases</th>
                        <th>
                            <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-init"
                                data-bs-toggle="popover" data-bs-title="Bills"
                                data-bs-content="
                            <div class='d-flex'>
                                <div class='bg-danger rounded-circle' style='width:15px;height:15px'></div>
                                <span class='ps-2'>Over Due</span>
                            </div>
                            <div class='d-flex'>
                                <div class='bg-primary rounded-circle' style='width:15px;height:15px'></div>
                                <span class='ps-2'>Due Today</span>
                            </div>
                             <div class='d-flex'>
                                <div class='border border-dark rounded-circle' style='width:15px;height:15px'></div>
                                <span class='ps-2'>Upcomming</span>
                            </div>
                        ">
                                Bills
                            </a>
                        </th>
                        <th class="text-center"><i class="bi bi-journals"></i></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        <tr>
                            <td><a class="text-reset text-center" href="{{ route('client.show', $client->id) }}">
                                    <div>{{ $client->id }}</div>
                                </a>
                            </td>
                            <td>{{ $client->name }}</td>
                            <td>
                                @if ($client->address)
                                    <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-reset"
                                        data-bs-toggle="popover" data-bs-title="<div class='text-center'>Address</div>"
                                        data-bs-content="
                                    <div class='text-center'>
                                        {{ $client->address }}
                                    </div>
                                    ">
                                        <div class="px-2"
                                            style="max-width: 300px;text-overflow: ellipsis;white-space:nowrap;overflow: hidden;">
                                            {{ $client->address }}
                                        </div>
                                    </a>
                                @else
                                    <span class="text-muted px-2">None</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($client->contacts_count > 0)
                                    <button class="btn-primary btn p-0 px-2" data-bs-toggle="modal"
                                        data-bs-target="#showContactsModal" data-contacts='{{ $client->contacts }}'>
                                        {{ $client->contacts_count }}
                                    </button>
                                @else
                                    <span class="text-muted">None</span>
                                @endif
                            </td>

                            <td
                                class='{{ $client->total_remaining_deposit > 0 ? 'table-success' : ($client->total_remaining_deposit == 0 ? 'table-secondary' : 'table-danger') }}'>

                                <a tabindex="0" data-bs-custom-class="custom-popover-wo-padding"
                                    class="popover-click text-muted" data-bs-toggle="popover"
                                    data-bs-title="<div class='text-center'>Deposit</div>"
                                    data-bs-content="<table class='table  table-borderless table-sm mt-2 mb-0 '>
                                    <tr>
                                        <td class='pe-3 text-nowrap ps-3'> Deposits:</td>
                                        <td class='text-success pe-3'>₱{{ number_format($client->admin_deposits_sum_amount, 2) }}</td>
                                    </tr>
                                    <tr >
                                        <td class='text-nowrap ps-3'> Fees:</td>
                                        <td class='text-danger pe-3'>₱{{ number_format($client->admin_fees_sum_amount, 2) }}</td>
                                    </tr>
                                 
                                    <tr class='{{ $client->total_remaining_deposit > 0 ? 'table-success' : ($client->total_remaining_deposit == 0 ? 'table-secondary' : 'table-danger') }}'>
                                        <td class='ps-3'>Total:</td>
                                        <td class='pe-3'>₱{{ number_format($client->total_remaining_deposit, 2) }}</td>
                                    </tr>
                               
                                </table>">
                                    ₱{{ number_format($client->total_remaining_deposit, 2) }} </a>
                            </td>
                            <td class="text-center">
                                @if ($client->law_cases_count > 0)
                                    <a tabindex="0" data-bs-custom-class="custom-popover-wo-padding"
                                        class="popover-click text-reset" data-bs-toggle="popover"
                                        data-bs-title="<div class='text-center'>Cases</div>"
                                        data-bs-content="
                                    <div class='p-2'>
                                    <div class='mb-2'>{!! $client->case_status_count_badges !!}</div>
                                    <div class='border-bottom border-top mb-2 py-2' >{!! $client->case_type_count_badges !!}</div>
                                    <div>{!! $client->role_count_badges !!}</div>
                                    </div>
                                    ">
                                        {{ $client->law_cases_count }}
                                    </a>
                                @else
                                    <span class="text-muted">None</span>
                                @endif
                            </td>
                            <td class="text-center position-relative">
                                <div class="badge-con">
                                    @if ($client->over_due_bills_count)
                                        <div class="bg-danger"></div>
                                    @endif
                                    @if ($client->due_today_bills_count)
                                        <div class="bg-primary"></div>
                                    @endif
                                    @if ($client->upcomming_bills_count)
                                        <div class="border border-dark"></div>
                                    @endif
                                </div>
                                @if ($client->billings_count)
                                    <a tabindex="0" data-bs-custom-class="custom-popover-wo-padding"
                                        class="popover-click text-reset" data-bs-toggle="popover"
                                        data-bs-title="<div class='text-center'>Bills</div>"
                                        data-bs-content="
                                    <div class='p-2'>
                                        @if ($client->over_due_bills_count)
<div>
<span class='badge rounded-pill text-bg-danger  m-1'>
                                                <span class='badge text-bg-light'>{{ $client->over_due_bills_count }}</span>
                                                Over Due
                                            </span>
                                        </div>
@endif
                                        @if ($client->due_today_bills_count)
<div>
<span class='badge rounded-pill text-bg-primary  m-1'>
                                                <span class='badge text-bg-light'>{{ $client->due_today_bills_count }}</span>
                                                Due Today
                                            </span>
                                        </div>
@endif
                                        @if ($client->upcomming_bills_count)
<div>
<span class='badge rounded-pill text-bg-light btn btn-outline-dark m-1' style='cursor:auto'>
                                                <span class='badge text-bg-primary'>{{ $client->upcomming_bills_count }}</span>
                                                Upcomming
                                            </span>
                                        </div>
@endif
                                        @if ($client->paid_bills_count)
<div>
<span class='badge rounded-pill text-bg-success m-1' style='cursor:auto'>
                                                <span class='badge text-bg-light'>{{ $client->paid_bills_count }}</span>
                                                Paid
                                            </span>
                                        </div>
@endif
                                    </div>
                                    ">
                                        {{ $client->billings_count }}
                                    </a>
                                @else
                                    <span class="text-muted">None</span>
                                @endif
                            </td>
                            <td class="text-center">

                                <button class="btn-outline-dark btn p-0 px-2" id="client{{ $client->id }}NotesCount"
                                    data-bs-toggle="modal" data-bs-target="#showNotesModal"
                                    data-route='{{ route('note', ['client', $client->id]) }}'>
                                    {{ $client->notes_count }}
                                </button>

                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm" data-bs-toggle="dropdown"
                                        aria-expanded="false"></button>
                                    <ul class="dropdown-menu shadow">
                                        <li>
                                            <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                                <span class="text-muted me-2">#{{ $client->id }}</span>
                                                <span>{{ $client->name }}</span>
                                            </h6>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('client.show', $client->id) }}">
                                                <i class="bi bi-eye me-2"></i>View
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('client.show', $client->id) }}?edit=true">
                                                <i class="bi bi-pencil me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                                data-bs-target="#deleteClientModal" data-client-id="{{ $client->id }}"
                                                data-modal-title="{{ $client->name }}">
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

        {{ $clients->appends(request()->query())->links() }}
    </div>
    @can('delete', getPermissionClass('Cases'))
        <x-client.delete-client-modal />
    @endcan
@endsection

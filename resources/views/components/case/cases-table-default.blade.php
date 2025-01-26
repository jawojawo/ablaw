@props(['lawCases', 'excludeCol' => []])
@php
    $paginatedlawCases = $lawCases->withCount(['contacts', 'notes'])->paginate(10, ['*'], 'cases-page');
@endphp
@if ($paginatedlawCases->isEmpty())
    <p class="text-center text-muted">No Cases found.</p>
@else
    <table class="table table-bordered table-center">
        <thead>
            <tr class="text-uppercase">

                <th class="table-td-min text-center">#</th>
                <th>case number</th>
                <th>
                    <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-init"
                        data-bs-toggle="popover" data-bs-title="Case Type"
                        data-bs-content="
                                <table class='table table-sm table-borderless'>
                                    <tr>
                                        <td>Litigation</td>
                                        <td><span class='badge  t-success m-1'>L</span></td>
                                    </tr>
                                    <tr>
                                        <td>Non Litigation</td>
                                        <td><span class='badge  t-danger m-1'>N</span></td>
                                    </tr>
                                </table>
                            ">
                        type
                    </a>
                </th>
                <th>client</th>
                <th>
                    <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-init"
                        data-bs-toggle="popover" data-bs-title="Party Role"
                        data-bs-content="
                            <table class='table table-sm table-borderless'>
                                <tr>
                                    <td>Petitioner</td>
                                    <td><span class='badge  t-warning m-1'>P</span></td>
                                </tr>
                                <tr>
                                    <td>Respondent</td>
                                    <td><span class='badge  t-info m-1'>R</span></td>
                                </tr>
                            </table>
                        ">
                        Role
                    </a>

                </th>
                <th>opposing party</th>
                @if (!in_array('associate', $excludeCol))
                    <th>associate</th>
                @endif
                {{-- <th>
                    <div>hearing</div>
                </th> --}}

                <th>
                    Status
                </th>
                <th class="text-center"><i class="bi bi-journals"></i></th>

            </tr>
            </tr>
        </thead>
        @foreach ($paginatedlawCases as $case)
            <tr>
                <td><a class="text-reset text-center" href="{{ route('case.show', $case->id) }}">
                        <div>{{ $case->id }}</div>
                    </a></td>
                <td class="text-nowrap">
                    <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-muted"
                        data-bs-toggle="popover" data-bs-title="<div class='text-center'>{{ $case->case_number }}</div>"
                        data-bs-content="<div class='fst-italic'>{{ $case->case_title }}</div>">
                        {{ $case->case_number }}
                    </a>

                </td>
                <td>{!! $case->case_type_badge !!}</td>
                <td>{{ $case->client->name }}</td>
                <td>{!! $case->case_party_role_badge !!}</td>
                <td>{{ $case->opposing_party }}</td>
                @if (!in_array('associate', $excludeCol))
                    <td>{{ $case->associate->name }}</td>
                @endif

                {{-- <td>{!! $case->formatted_next_hearing !!}</td> --}}
                <td class='text-center'>
                    <span id='statusCon{{ $case->id }}'>
                        {!! $case->status_badge !!}
                    </span>
                </td>
                <td>
                    <button class="btn-outline-dark btn p-0 px-2" id="case{{ $case->id }}NotesCount"
                        data-bs-toggle="modal" data-bs-target="#showNotesModal"
                        data-route='{{ route('note', ['case', $case->id]) }}'>
                        {{ $case->notes_count }}
                    </button>

                </td>
                {{-- <td>
                    {{ $case->user->name }}
                </td> --}}

            </tr>
        @endforeach

    </table>
    {{ $paginatedlawCases->links() }}
@endif

@props(['activities', 'pdf' => false])

@forelse ($activities as $activity)
    @switch($activity->description)
        @case('created')
            <div class="card mb-2">
                <div class="card-header text-bg-light d-flex justify-content-between" style="border-bottom:none">
                    <div>
                        #{{ $activity->subject_id }}
                        <span class="fw-bold text-primary">
                            {{ config('enums.activityModels')[$activity->subject_type]['name'] ?? '' }}
                        </span>
                        has been
                        <div class="badge rounded-pill text-bg-success">created</div>
                        by <span class="text-muted fw-bold">{{ getUserName($activity->causer_id) }}</span>
                    </div>
                    <div class="d-flex gap-2">
                        <div class="text-nowrap">{{ formattedDateTime($activity->created_at) }}</div>
                        <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm" data-bs-toggle="collapse"
                            data-bs-target="#activity-{{ $activity->id }}"></button>
                    </div>
                </div>
                <div class="card-body p-0 collapse" id ="activity-{{ $activity->id }}">
                    @php
                        $attributes = $activity->properties['attributes'] ?? [];
                        $customMessages = $attributes['custom_messages'] ?? [];
                        $attributesTitles = config('enums.activityModels')[$activity->subject_type]['attributes'] ?? [];
                    @endphp
                    <ul class="list-group">

                        @foreach ($attributes as $attribute => $value)
                            @if (isset($attributesTitles[$attribute]['title']))
                                @switch($attributesTitles[$attribute]['type'] ?? '')
                                    @case('function')
                                        <li class="list-group-item d-flex justify-content-between gap-4">
                                            <div class="fw-bold">{{ $attributesTitles[$attribute]['title'] ?? '' }}</div>
                                            <div class="text-end">
                                                {{ $attributesTitles[$attribute]['function']($value) ?? '' }}
                                            </div>
                                        </li>
                                    @break

                                    @default
                                        <li class="list-group-item d-flex justify-content-between gap-4">
                                            <div class="fw-bold">{{ $attributesTitles[$attribute]['title'] ?? '' }}</div>
                                            <div class="text-end">
                                                {{ $value ?? 'N/A' }}
                                            </div>
                                        </li>
                                    @break
                                @endswitch
                            @endif
                        @endforeach
                        @foreach ($customMessages as $message)
                            <li class="list-group-item">{!! $message !!}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @break

        @case('updated')
            <div class="card mb-2">
                <div class="card-header text-bg-light d-flex justify-content-between " style="border-bottom:none">
                    <div>
                        #{{ $activity->subject_id }}
                        <span class="fw-bold text-primary">
                            {{ config('enums.activityModels')[$activity->subject_type]['name'] ?? '' }}
                        </span>
                        has been
                        <div class="badge rounded-pill text-bg-warning">updated</div>
                        by <span class="text-muted fw-bold">{{ getUserName($activity->causer_id) }}</span>
                    </div>
                    <div>{{ formattedDateTime($activity->created_at) }}</div>
                </div>
                <div class="card-body p-0">
                    @php
                        $new = $activity->properties['attributes'] ?? [];
                        $old = $activity->properties['old'] ?? [];
                        $customMessages = $new['custom_messages'] ?? [];
                        $attributesTitles = config('enums.activityModels')[$activity->subject_type]['attributes'] ?? [];
                    @endphp
                    <ul class="list-group">
                        @foreach ($new as $key => $value)
                            @if (isset($attributesTitles[$key]['title']))
                                @switch($attributesTitles[$key]['type'] ?? '')
                                    @case('function')
                                        <li class="list-group-item d-flex justify-content-between gap-4">
                                            <div class="fw-bold">{{ $attributesTitles[$key]['title'] ?? '' }}</div>
                                            <div class="text-end">
                                                <span
                                                    class="text-muted with-arrow">{{ $attributesTitles[$key]['function']($old[$key] ?? '') }}</span>
                                                <span
                                                    class="fw-bold">{{ $attributesTitles[$key]['function']($new[$key] ?? '') }}</span>
                                            </div>
                                        </li>
                                    @break

                                    @case('message')
                                        {!! $attributesTitles[$key]['function']($new[$key]) !!}
                                    @break

                                    @default
                                        <li class="list-group-item d-flex justify-content-between gap-4">
                                            <div class="fw-bold">{{ $attributesTitles[$key]['title'] ?? '' }}</div>
                                            <div class="text-end">
                                                <span class="text-muted with-arrow">{{ $old[$key] ?? '' }}</span>

                                                <span class="fw-bold">{{ $new[$key] ?? '' }}</span>
                                            </div>
                                        </li>
                                    @break
                                @endswitch
                            @endif
                        @endforeach
                        @foreach ($customMessages as $message)
                            <li class="list-group-item">{!! $message !!}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @break

        @case('deleted')
            <div class="card mb-2">
                <div class="card-header text-bg-light d-flex justify-content-between" style="border-bottom:none">
                    <div>
                        #{{ $activity->subject_id }}
                        <span class="fw-bold text-primary">
                            {{ config('enums.activityModels')[$activity->subject_type]['name'] ?? 'Unknown Model' }}
                        </span>
                        has been
                        <div class="badge rounded-pill text-bg-danger">deleted</div>
                        by <span class="text-muted fw-bold">{{ getUserName($activity->causer_id) }}</span>
                    </div>
                    <div>{{ formattedDateTime($activity->created_at ?? now()) }}</div>
                </div>
                <div class="card-body p-0">
                    @php
                        $attributes = $activity->properties['old'] ?? [];
                        $customMessages = $activity->properties['attributes']['custom_messages'] ?? [];
                        $attributesTitles = config('enums.activityModels')[$activity->subject_type]['attributes'] ?? [];
                    @endphp
                    <ul class="list-group">
                        @foreach ($attributes as $attribute => $value)
                            @if (isset($attributesTitles[$attribute]['title']))
                                <li class="list-group-item d-flex justify-content-between gap-4">

                                    <div class="fw-bold">{{ $attributesTitles[$attribute]['title'] ?? '' }}</div>
                                    @switch($attributesTitles[$attribute]['type'] ?? '')
                                        @case('function')
                                            <div class="text-end">
                                                {{ $attributesTitles[$attribute]['function']($value) ?? '' }}
                                            </div>
                                        @break

                                        @default
                                            <div class="text-end">
                                                {{ $value ?? 'N/A' }}
                                            </div>
                                        @break
                                    @endswitch
                                </li>
                            @endif
                        @endforeach
                        @foreach ($customMessages as $message)
                            <li class="list-group-item">{!! $message !!}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @break

        @default
    @endswitch

    @empty
        <div class="text-center p-4"> no activities found</div>
    @endforelse

    @if (!$pdf)
        {{ $activities->links() }}
    @endif

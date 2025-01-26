<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Description</th>
            <th>Changes</th>
            <th>Changes</th>
            <th>Performed By</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($activities as $activity)
            <tr>
                <td>{{ $activity->id }}</td>
                <td>{{ $activity->description }}</td>
                <td>{{ $activity->properties }}</td>
                <td>
                    @php
                        $changes = $activity->properties['attributes'] ?? [];
                        $original = $activity->properties['old'] ?? [];
                    @endphp
                    @if ($changes)
                        <ul>
                            @foreach ($changes as $key => $value)
                                @if (isset($original[$key]))
                                    <li>
                                        <strong>{{ $key }}:</strong>
                                        {{ $original[$key] }} → {{ $value }}
                                    </li>
                                @else
                                    <li>
                                        <strong>{{ $key }}:</strong> {{ $value }} (new)
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <span class="text-muted">No changes recorded.</span>
                    @endif
                </td>
                <td>{{ $activity->causer->name ?? 'System' }}</td>
                <td>{{ $activity->created_at->format('M d, Y H:i') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">No activities found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $activities->links() }} <!-- Pagination links -->

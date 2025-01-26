@props(['billings'])
@foreach ($billings->get() as $billing)
    {{ $billing }}
@endforeach

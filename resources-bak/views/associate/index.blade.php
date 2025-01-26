@extends('layout.app')
@section('title')
    associate
@endsection
@section('main')
    <div class="container h-100">
        <h5 class="my-5">Associates</h5>
        @if ($associates->isEmpty())
            <div class="text-center fs-3 card p-5"> No Associates Found</div>
        @else
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($associates as $associate)
                        <tr>
                            <th scope="row">{{ $associate->id }}</th>
                            <td>{{ $associate->first_name }} {{ $associate->last_name }} {{ $associate->suffix }}</td>
                            <td>{{ $associate->email }}</td>
                            <td><span class="text-secondary">+63</span> {{ $associate->phone }}</td>

                        </tr>
                    @endforeach


                </tbody>

            </table>
        @endif
        {{ $associates->appends(request()->query())->links() }}
    </div>
@endsection

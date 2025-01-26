@extends('layout.app')
@section('title')
    clients
@endsection
@section('main')
    <div class="container h-100">

        <h5 class="my-5">Clients</h5>
        @if ($clients->isEmpty())
            <div class="text-center fs-3 card p-5"> No Clients Found</div>
        @else
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="table-td-min text-center">#</th>
                        <th>Name</th>
                        <th>email</th>
                        <th>phone</th>
                        <th>Cases</th>
                        <th>Bills</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        <tr>
                            <th scope="row"><a href="{{ route('client.show', $client->id) }}">{{ $client->id }}</a></th>
                            <td>{{ $client->first_name }} {{ $client->last_name }} {{ $client->suffix }}</td>
                            <td>{{ $client->email }}</td>
                            <td><span class="text-secondary">+63</span> {{ $client->phone }}</td>
                            <td> {{ $client->lawCases->count() }}</td>
                            <td> {{ $client->billings->count() }}</td>
                        </tr>
                    @endforeach


                </tbody>

            </table>
        @endif
        {{ $clients->appends(request()->query())->links() }}
    </div>

@endsection

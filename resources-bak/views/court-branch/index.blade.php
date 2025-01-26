@extends('layout.app')
@section('title')
    associate
@endsection
@section('main')
    <div class="container h-100">
        <h5 class="my-5">Court Branches</h5>
        @if ($courtBranches->isEmpty())
            <div class="text-center fs-3 card p-5"> No Court Branch Found</div>
        @else
            <table class="table table-striped">
                <thead class="text-uppercase">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">region/province</th>
                        <th scope="col">city/municipality</th>
                        <th scope="col">court type</th>
                        <th scope="col">branch</th>
                        <th scope="col">judge</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($courtBranches as $courtBranch)
                        <tr>
                            <th scope="row">{{ $courtBranch->id }}</th>
                            <td>{{ $courtBranch->region }}</td>
                            <td>{{ $courtBranch->city }}</td>
                            <td>{{ $courtBranch->type }}</td>
                            <td>{{ $courtBranch->branch }}</td>
                            <td>{{ $courtBranch->judge }}</td>

                        </tr>
                    @endforeach


                </tbody>

            </table>
        @endif
        {{ $courtBranches->appends(request()->query())->links() }}
    </div>
@endsection

@extends('layout.app')
@section('title')
    associate
@endsection
@section('main')
    <div class="container h-100">
        <div class="d-flex justify-content-between">
            <h5 class="my-5">Court Branches</h5>
            <a href="{{ route('settings.adminFeeCategory.create') }}" class="btn btn-success my-5"><i class="bi bi-plus"></i>
                Add
                Administrative Fee Category</a>
        </div>
        @if ($adminFeeCategories->isEmpty())
            <div class="text-center fs-3 card p-5"> No Administrative Fee Category Found</div>
        @else
            <table class="table table-striped">
                <thead class="text-uppercase">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">name</th>
                        <th scope="col">amount</th>
                        <th scope="col"><i class="bi bi-three-dots-vertical btn"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($adminFeeCategories as $adminFeeCategory)
                        <tr>
                            <th scope="row">{{ $adminFeeCategory->id }}</th>
                            <td>{{ $adminFeeCategory->name }}</td>
                            <td>₱ {{ number_format($adminFeeCategory->amount, 2) }}</td>
                            <td>
                                <button class="btn " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu mb-0 pb-0">
                                    <li>
                                        <h6 class="dropdown-header">
                                            <span>#{{ $adminFeeCategory->id }}</span>
                                            <span>
                                                {{ $adminFeeCategory->name }}
                                            </span>
                                        </h6>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="#">View</a></li>
                                    <li><a class="dropdown-item mb-4" href="#">Edit</a></li>

                                    <li><a class="dropdown-item bg-danger text-bg-danger" href="#">Delete</a></li>
                                </ul>
                            </td>
                        </tr>
                    @endforeach


                </tbody>

            </table>
        @endif
        {{ $adminFeeCategories->appends(request()->query())->links() }}
    </div>
@endsection

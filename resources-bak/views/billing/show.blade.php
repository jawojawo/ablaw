@extends('layout.app')
@section('title')
    deposit
@endsection
@section('main')
    <div class="container py-4">
        {{-- <div class="card mx-auto shadow-sm" style="max-width: 900px;">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    #{{ $billing->id }} Bill
                </h5>
                <span class="badge {{ $billing->status_class }}">{{ $billing->status }}</span>
            </div>
        </div> --}}
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Billing Overview</h2>
            <button class="btn btn-primary">Create New Billing</button>
        </div>
        <hr>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <h5>Total Billings</h5>
                        <p class="display-6">150</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center bg-warning text-white">
                    <div class="card-body">
                        <h5>Unpaid Balance</h5>
                        <p class="display-6">₱ 75,000</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <h5>Paid Balance</h5>
                        <p class="display-6">₱ 50,000</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center bg-danger text-white">
                    <div class="card-body">
                        <h5>Overdue Billings</h5>
                        <p class="display-6">5</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Billing Records</h5>
                <input type="text" class="form-control w-25" placeholder="Search...">
            </div>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Billing ID</th>
                            <th>Client Name</th>
                            <th>Case Title</th>
                            <th>Amount</th>
                            <th>Amount Paid</th>
                            <th>Balance Due</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1001</td>
                            <td>John Doe</td>
                            <td>Case A</td>
                            <td>₱ 10,000</td>
                            <td>₱ 5,000</td>
                            <td><span class="text-danger">₱ 5,000</span></td>
                            <td><span class="text-warning">Nov 4, 2024</span></td>
                            <td><span class="badge bg-warning">Partially Paid</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#billingDetailModal">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-secondary"><i class="fa fa-download"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <!-- Repeat for each billing record -->
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <nav>
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Billing Detail Modal -->
        <div class="modal fade" id="billingDetailModal" tabindex="-1" aria-labelledby="billingDetailModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="billingDetailModalLabel">Billing Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Billing details content -->
                        <p><strong>Billing ID:</strong> 1001</p>
                        <p><strong>Client Name:</strong> John Doe</p>
                        <p><strong>Case Title:</strong> Case A</p>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Service A</td>
                                    <td>2</td>
                                    <td>₱ 2,500</td>
                                    <td>₱ 5,000</td>
                                </tr>
                                <!-- More items here -->
                            </tbody>
                        </table>
                        <p><strong>Notes:</strong> Payment due in 30 days.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary">Edit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

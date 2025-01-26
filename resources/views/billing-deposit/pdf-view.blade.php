@extends('layout.pdf')
@section('pdfContent')
    @include('billing-deposit.partials.billing-deposits-table', ['pdf' => true])
@endsection

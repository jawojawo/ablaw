@extends('layout.pdf')
@section('pdfContent')
    @include('billing.partials.billings-table', ['pdf' => true])
@endsection

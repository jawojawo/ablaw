@extends('layout.pdf')
@section('pdfContent')
    @include('office-expense.partials.office-expense-table', ['pdf' => true])
@endsection

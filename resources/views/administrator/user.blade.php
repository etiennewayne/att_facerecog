@extends('layouts.app')

@section('content')
    <user-page prop-salary-levels='@json($salaryLevels)'></user-page>
@endsection


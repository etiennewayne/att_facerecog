@extends('layouts.app')

@section('content')
    <user-page prop-salary-levels='@json($salaryLevels)'
        prop-branches='@json($branches)'></user-page>
@endsection


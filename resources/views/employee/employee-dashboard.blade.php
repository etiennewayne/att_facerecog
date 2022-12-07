@extends('layouts.employee-layout')

@section('content')
    <employee-dashboard prop-user='@json($user)'></employee-dashboard>
@endsection

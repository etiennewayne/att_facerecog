@extends('layouts.app')

@section('content')
    @if($id > 0)
        <daily-time-record-create prop-id="{{ $id }}" prop-data='@json($data)'></daily-time-record-create>
    @else
        <daily-time-record-create prop-id="0" prop-data=''></daily-time-record-create>
    @endif

@endsection



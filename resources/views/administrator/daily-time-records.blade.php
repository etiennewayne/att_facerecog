@extends('layouts.app')

@section('content')
    <daily-time-records prop-branches='@json($branches)'>
    </daily-time-records>
@endsection



@extends('layouts.app')

@section('extra')
    <script defer src="{{ asset('/js/face/face-api.min.js') }}"></script>
    <script src="{{ asset('/js/face/faceDetectionControls.js') }}"></script>
    {{-- <link rel="stylesheet" href="{{ asset('/css/face/styles.css') }}">  --}}
    <script type="text/javascript" src="/js/face/jquery-2.1.1.min.js"></script>
@endsection


@section('content')
    <face-register></face-register>
@endsection
    


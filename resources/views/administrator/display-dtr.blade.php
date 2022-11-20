@extends('layouts.app')

@section('extra')
    <style>

        @media print {

            .print-form{
                width: 816px;
                margin: 30px auto 0;
                border: 1px solid red;
            }
            .print-title{
                margin: auto;
                text-align: center;
            }
            .navbar{
                display: none;
            }
        }

        /*outisde during not print pge*/
        .print-title{
            margin: auto;
            text-align: center;
        }

        .dtr-month{
            text-align: center;
        }

        .dtr-name{
            font-weight: bold;
            text-decoration: underline;
            text-align: center;
        }

        .dtr-table{
            border: 1px solid black;
        }

    </style>
@endsection

@section('content')

    <display-dtr prop-id="{{ $id }}" prop-data='@json($data)'></display-dtr>
@endsection



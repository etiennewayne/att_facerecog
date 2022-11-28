@extends('layouts.app')

@section('extra')
    <style>

        @media print {
            .print-form{
                
                margin: 10px auto 0;
                /* border: 1px solid red; */
            }
            .print-title{
                margin: auto;
                text-align: center;
            }
            .navbar{
                display: none;
            }

            .filter-print{
                display: none;
            }

            .payslip{
                margin: 10px auto 0;
            }
        }

        /*outisde during not print pge*/
        .print-title{
            margin: auto;
            text-align: center;
        }

        .filter-print{
            display: flex;
            margin: 10px auto;
            justify-content: center;
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
            /*border: 1px solid black;*/
            margin: auto;
            font-size: 11px;
        }
        .dtr-table tr td{
            border: 1px solid black;
            padding: 5px;
        }

        .dtr-table tr .time-header{
            text-align: center;
            font-weight: bold;
            width: 80px;
        }

        .dtr-table tr .time{
            text-align: center;

        }


        .payslip-header-text{
            font-weight: bold;
            text-align: center;
        }

        .table-payslip tr .align-right{
            text-align: right;
        }

    </style>
@endsection

@section('content')
    <display-dtr prop-id="{{ $id }}" prop-user='@json($user)'></display-dtr>
@endsection



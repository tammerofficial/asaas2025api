@if (isset($payment_details))
    @if (empty($payment_details))
        @php
            header("Location: " . url('/'), true, 302);
            exit();
        @endphp
    @endif
@endif

@extends('tenant.frontend.frontend-page-master')
@section('title')
    {{__('Payment Success From:')}} {{$payment_details->name}}
@endsection

@section('page-title')
    {{__('Payment Success For:')}} {{$payment_details->name}}
@endsection
@section('content')
    <style>
        .billing-details li{
            text-transform: capitalize;
        }
        .vat-tax{
            font-size: 10px;
        }
    </style>

    <div class="error-page-content" data-padding-bottom="100" data-padding-top="100">
        <div class="container-fluid">
         @include('themes.components.common-payment-success');
        </div>
    </div>

@endsection

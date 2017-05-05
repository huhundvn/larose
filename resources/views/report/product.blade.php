@extends('layouts.app')

@section('title')
    Sản phẩm được quan tâm nhiều
@endsection

@section('location')
    <li> Báo cáo </li>
    <li> Sản phẩm bán chạy </li>
@endsection

@section('content')
    <div ng-controller="ReportController">
        <div class="row">
            <canvas class="chart-bar" chart-data="data" chart-labels="labels"></canvas>
        </div>
        @{{labels}}
    </div>
@endsection

{{-- !ANGULARJS! --}}
@section('script')
    <script src="{{ asset('angularJS/ReportController.js') }}"></script>
@endsection
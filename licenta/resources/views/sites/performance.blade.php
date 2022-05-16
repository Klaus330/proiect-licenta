@extends('layouts.dashboard')
@php
    // check if data array is empty reccursivly


@endphp
@section('basic_head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
@endsection

@section('dashboard-content')
        @livewire('performance-chart', ['site' => $site])
        @include('sites.snippets.audit-report', ['site' => $site])
@endsection

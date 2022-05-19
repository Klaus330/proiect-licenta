@extends('layouts.main')

@section('basic-head')
    @yield('dashboard-head')
@endsection

@section('content')

    @include('snippets.dashboard-navbar')

    @yield('dashboard-content')

    @yield('dashboard-script')
@endsection
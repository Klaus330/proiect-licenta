@extends('layouts.main')

@section('content')

    @include('snippets.dashboard-navbar')

    @yield('dashboard-content')
@endsection
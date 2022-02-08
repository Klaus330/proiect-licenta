@extends('layouts.basic')

@section('basic_content')
    @include('snippets.nav')
    <main  class="bg-gray-200 h-full max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

@endsection
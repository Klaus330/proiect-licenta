@extends('layouts.dashboard')

@php
    $isUp = $site->isUp();
    $isPending = $site->isPending();
    $stats = $site->stats->last();
@endphp

@section('dashboard-content')
<section class="p-5 bg-white mt-4">
    <div>
        <h1 class="text-2xl font-semibold">Uptime</h1>
    </div>
    <div>
        @if($stats)
            <div class="mt-3 flex flex-col">
                    <span
                        class="border-l-2 w-full p-2 rounded {{ $isUp ? 'bg-green-200  border-green-400' : 'bg-red-200  border-red-400' }}">
                        {{$site->url}} is {{$isUp ? "up" : 'down'}}
                    </span>

                <span class="text-gray-500 mt-3">
                        Learn more about how to perform uptime checks <a href="/docs"
                                                                         class="underline">in our doc</a>.
                    </span>
                @include('sites.snippets.site.general')
                @include('sites.snippets.site.results')
                @include('sites.snippets.site.details')
            </div>
        @else
            <div class="flex flex-col items-center justify-center h-full mt-5">
                <svg class="w-20 h-20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                     fill-rule="evenodd" clip-rule="evenodd">
                    <path
                        d="M15.853 16.56c-1.683 1.517-3.911 2.44-6.353 2.44-5.243 0-9.5-4.257-9.5-9.5s4.257-9.5 9.5-9.5 9.5 4.257 9.5 9.5c0 2.442-.923 4.67-2.44 6.353l7.44 7.44-.707.707-7.44-7.44zm-6.353-15.56c4.691 0 8.5 3.809 8.5 8.5s-3.809 8.5-8.5 8.5-8.5-3.809-8.5-8.5 3.809-8.5 8.5-8.5z"/>
                </svg>
                <h2
                    class="mt-5 mb-2 font-bold text-2xl text-center">
                    We are collecting data for you.
                </h2>
                <p class="text-gray-600 text-sm mb-5">
                    Please come back soon!
                </p>
                <a href="{{ route('sites.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Go back to sites
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
@extends('layouts.main')


@section('content')
<div class="py-8">
    <div class="bg-white rounded shadow-lg p-8 flex flex-col px-4 py-5">
        {{-- <div class="flex justify-between flex-col md:flex-row md:items-center">
            <div class="overflow-auto flex">
                <input type="text" placeholder="Filter sites" class="border border-gray-300 mr-2 p-1 md:p-2 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block sm:text-sm border-gray-300 rounded-md">
                <button class="text-xs whitespace-nowrap inline-flex items-center px-2 py-1 md:px-4 md:py-2 border border-transparent md:text-base font-medium shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Show all sites
                </button>
            </div>
            <div class="hidden md:block">
               <span class="text-xl font-bold">
                   {{ $currentTeamName }}'s Websites
               </span>
            </div>
        </div> --}}
        <div class="mt-7">
            <div class="flex flex-col">
                @livewire('sites-table')
            </div>
        </div>


        @livewire('add-new-site-form')

    </div>
</div>
@endsection
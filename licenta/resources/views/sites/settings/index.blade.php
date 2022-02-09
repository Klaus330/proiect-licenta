@extends('layouts.dashboard')


@section('dashboard-content')
    <section class="p-5 bg-white rounded">
        <div>
            @include('sites.settings.tabs')
            
            <form action="{{ route('settings.general', ['site' => $site->id]) }}" method="POST"
                class="relative bg-white rounded-lg  w-75 pb-5 sm:mb-5 lg:mb-0 my-5">
                @csrf
                @method("PATCH")
                <div class="grid grid-cols-1 md:grid-cols-5">
                    <select name="schema"
                            class="col-span-1 my-3 focus:ring-indigo-500 focus:border-indigo-500 relative block w-full rounded bg-transparent focus:z-10 sm:text-sm border-gray-300 ">
                        <option value="http" {{ $site->isSecured() ? '' : 'selected' }}>http</option>
                        <option value="https" {{ $site->isSecured() ? 'selected' : '' }}>https</option>
                    </select>

                    <input type="text"
                        name="host"
                        class="col-span-4 my-3 md:ml-2 focus:ring-indigo-500 focus:border-indigo-500 relative block w-full rounded bg-transparent focus:z-10 sm:text-sm border-gray-300 "
                        placeholder="Your website"
                        value="{{$site->host}}">
                </div>


                <div class="grid grid-cols-1 w-full flex items-start my-5 md:grid-cols-5">
                    <label class="flex-1 w-full mr-5 col-span-1" for="check">Friendly name</label>
                    <div class="col-span-1 md:col-span-4">
                        <input type="text" name="name" id="check"
                            class="md:ml-2 focus:ring-indigo-500 focus:border-indigo-500 relative block w-full rounded bg-transparent focus:z-10 sm:text-sm border-gray-300"
                            value="{{$site->name ?? ''}}">
                        <p class="md:ml-2 text-gray-500 text-xs mt-2">
                            If you specify a friendly name we'll display this instead of the url.
                        </p>
                    </div>
                </div>
                <x-jet-validation-errors class="mt-4"/>

                {{-- <div class="grid grid-cols-1 w-full flex items-start my-5 md:grid-cols-5 flex items-start">
                    <h4 class="mb-2 font-semibold text-lg col-span-1">Headers</h4>
                    <multiple-fields-creator
                        action-message="Add custom header"
                        action-description="When performing the uptime, mixed content and broken links checks we'll add these headers to each request we make to {{$site->host}}."
                        class="col-span-1 md:col-span-4"
                        :data="{{json_encode($site->headers)}}"
                    ></multiple-fields-creator>
                </div> --}}


                <div class="flex justify-end items-end">
                    <button
                        class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update
                    </button>
                </div>

            </form>
        </div>
    </section>
@endsection
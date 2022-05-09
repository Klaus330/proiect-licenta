@extends("layouts.dashboard")

@section('dashboard-content')
    <section class="p-5 bg-white rounded">
        <div>
            @include('sites.settings.tabs')
            {{--  --}}
            <form action="{{route('update.uptime.settings', ['site' => $site->id])}}" method="POST"
                class="relative space-y-8 bg-white rounded-lg w-75 pb-5 sm:mb-5 lg:mb-0">
                @csrf
                @method("PUT")
                <div class="w-full my-5 ">
                    <h4 class="mb-2 font-semibold text-lg col-span-1 mb-5">Tweak the uptime monitoring</h4>
                    <div class="w-full flex items-start grid grid-cols-1  md:grid-cols-5">
                        <label class="flex-1 w-full mr-5" for="verb">HTTP verb/method</label>
                        <div class="col-span-1 md:col-span-1">
                            <select name="verb" id="verb"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 relative block w-full rounded bg-transparent focus:z-10 sm:text-sm border-gray-300">
                                <option value="GET" {{$site->verb === "GET" ? 'selected' :''}}>GET</option>
                                <option value="POST" {{$site->verb === "POST" ? 'selected' :''}}>POST</option>
                                <option value="PUT" {{$site->verb === "PUT" ? 'selected' :''}}>PUT</option>
                                <option value="HEAD" {{$site->verb === "HEAD" ? 'selected' :''}}>HEAD</option>
                            </select>
                            <p class="text-gray-500 text-xs mt-2">
                                Which HTTP request method to use.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 w-full flex items-start my-5 md:grid-cols-5">
                        <label class="flex-1 w-full mr-5 col-span-1" for="check">Verify text on response</label>
                        <div class="col-span-1 md:col-span-4">
                            <input type="text" name="check" id="check"
                                class="focus:ring-indigo-500 focus:border-indigo-500 relative block w-full rounded bg-transparent focus:z-10 sm:text-sm border-gray-300"
                                placeholder="A piece of text to look for. Eg. 'book'"
                                value="{{ $site->check ?? '' }}">
                            <p class="text-gray-500 text-xs mt-2">
                                We'll mark the uptime check as failed if the given text is not present on the response.
                            </p>
                        </div>
                    </div>


                    {{-- <div class="grid grid-cols-1 w-full flex items-start my-5 md:grid-cols-5 flex items-start">
                        <h4 class="mb-2 font-semibold text-lg col-span-1">Payload</h4>
                        <multiple-fields-creator
                            action-message="Add payload field"
                            action-description="We'll send this form data along with the uptime check for all non-GET requests."
                            class="col-span-1 md:col-span-4"
                            :data="{{json_encode($site->payload)}}"
                        ></multiple-fields-creator> 
                    </div> --}}

                    {{-- <div class="w-full flex items-start my-5 grid grid-cols-1  md:grid-cols-5">
                        <label class="w-full p-1 col-label-1" for="timeout">Timeout in seconds</label>
                        <div class="mt-1 relative rounded-md shadow-sm col-span-1 md:col-span-2 ">
                            <div class="relative">
                                <input type="text" name="timeout" id="timeout"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-12 sm:text-sm border-gray-300 rounded-md"
                                    placeholder="2" aria-describedby="price-currency"
                                    value="{{$site->timeout ?? ''}}">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm" id="price-currency">
                                        seconds
                                    </span>
                                </div>
                            </div>
                            <p class="text-gray-500 text-xs mt-2 col-span-3">
                                If we don't get a response in the given amount of seconds, we'll mark the uptime check
                                as failed.
                            </p>
                        </div>
                    </div> --}}
                </div>

                <div class="flex items-start justify-end">
                    <button type="submit"
                            class="inline-flex items-start px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
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
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Site
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Uptime
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ssl
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Content
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cron
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                    {{-- @foreach($sites as $site)
                                        <!-- Odd row -->
                                        <tr class="bg-white">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-400">
                                                <a href="/uptime/{{$site->id}}">{{$site->name ?? $site->url}}</a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class=" flex items-center">
                                                    <status status="{{ $site->getStatus() }}"></status>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class=" flex items-center">
                                                    <status :status="{{ $site->hasSslCertificate() }}" success-message="Checked" pending-message="Unchecked" false-is-pending="1"></status>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 flex items-center">
                                                <status status="{{ $site->status }}" success-message="{{ucfirst($site->status)}}"></status>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class=" flex items-center">
                                                    <status :status="{{ $site->hasCronMonitors() ? 1 : 0 }}" false-is-pending="1" pending-message="None"></status>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach --}}
                                </tbody>
                            </table>
                        </div>
                        <div class="my-5">
                            {{-- {{ $sites->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="mt-10 mb-10 px-4">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Let's see your website's health</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            We will check all the essential health parameters of your website.
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2  shadow-lg">
                    <form action="/webmonitor" method="POST">
                        @csrf

                        <div class="shadow overflow-hidden sm:rounded-md">
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-4">
                                        <label for="website" class="block text-sm font-medium text-gray-700">
                                            Your Website
                                        </label>

                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <input type="text" name="url" id="website"
                                                   class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                   placeholder="https://www.example.com"
                                            >
                                        </div>
                                        <x-jet-validation-errors class="mb-4" />
                                        {{-- @error('url')
                                            <p class="text-red-500">{{$message}}</p>
                                        @enderror

                                        @if($errors->any())
                                            <p class="text-red-500">{{$errors->first()}}</p>
                                        @endif --}}
                                    </div>

                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Register Monitor
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
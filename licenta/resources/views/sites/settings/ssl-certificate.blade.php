@extends("layouts.dashboard")

@section('dashboard-content')
    <section class="p-5 bg-white rounded">
        <div>
            @include('sites.settings.tabs')

            <form action="{{route('update.certificate.settings', ['site' => $site->id])}}" method="POST"
                class="relative space-y-8 bg-white rounded-lg w-75 pb-5 sm:mb-5 lg:mb-0">
                @csrf
                @method("PUT")
                <div class="w-full my-5 ">
                    <div class="w-full flex items-start my-5 grid grid-cols-1  md:grid-cols-5">
                        <label class="w-full p-1 col-label-1" for="timeout">Expires soon threshold in days</label>
                        <div class="mt-1 relative rounded-md shadow-sm col-span-1 md:col-span-4 ">
                            <div class="relative">
                                <input type="number" name="expires_treshold"
                                    min="0"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-12 sm:text-sm border-gray-300 rounded-md"
                                    placeholder="2"
                                    value="{{ $site->sslCertificate->expires }}">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm" id="price-currency">
                                days
                            </span>
                                </div>
                            </div>
                            <p class="text-gray-500 text-sm mt-2 col-span-4">
                                We'll send you a notification if the certificate for {{$site->host}} expires within
                                these amount of days. If this field is empty, we default to 10 days.
                            </p>
                        </div>
                    </div>
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
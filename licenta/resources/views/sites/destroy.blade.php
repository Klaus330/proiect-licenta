@extends("layouts.dashboard")

@section('dashboard-content')
    <section class="p-5 bg-white rounded">        
        <div>
            @include('sites.settings.tabs')

            <form x-data action="{{route('sites.destroy', ['site' => $site->id])}}" method="POST" 
                @submit="if(confirm('Are you sure?')) { } else {  $event.stopImmediatePropagation(); $event.preventDefault(); }"
                class="relative space-y-8 bg-white rounded-lg w-75 pb-5 sm:mb-5 lg:mb-0">
                @csrf
                @method("DELETE")

                <div class="flex items-start justify-start flex-col">
                    <button type="submit"
                            class="inline-flex items-start px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Delete
                    </button>
                    <p class="text-gray-500 text-sm mt-2 col-span-4">
                        This will completely remove all the monitors from {{$site->name ?? $site->url}}. This action cannot
                        be reversed.
                    </p>
                </div>
            </form>

        </div>
    </section>
@endsection
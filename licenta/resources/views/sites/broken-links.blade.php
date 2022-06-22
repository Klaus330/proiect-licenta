@extends("layouts.dashboard")

@section('dashboard-content')
    @if(count($brokenLinks) > 0)
      
        <section class="p-5 bg-white rounded overflow-hidden w-full">
            <div class="flex items-center justify-between mb-3 w-full">
                <p class="font-bold">We {{$bokenLinksCount}} internal broken links</p>   
                <a href="{{ route('site.download-broken-links', ['site' => $site]) }}" class="text-white bg-purple-600 py-2 px-3 text-xs rounded text-center hover:bg-purple-800">
                    <i class="fas fa-download"></i> Download broken links .csv report
                </a>
            </div>
            
            <div class="overflow-x-auto mb-3">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                    <tr class="divide-x divide-gray-200">
                        <th scope="col" class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pl-6">STATUS CODE</th>
                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">INTERNAL BROKEN LINKS</th>
                        <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">FOUND ON</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($brokenLinks as $link)
                            <tr class="divide-x divide-gray-200">
                                <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pl-6">{{ $link->http_code }}</td>
                                <td class="whitespace-nowrap p-4 text-sm text-purple-500 hover:text-purple-700"><a href="{{ $link->route }}">{{ $link->route }}</a></td>
                                <td class="whitespace-nowrap p-4 text-sm text-purple-500 hover:text-purple-700"><a href="{{ $link->found_on }}">{{ $link->found_on }}</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3"> No broken links found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $brokenLinks->links() }}
        </section>
    @else
        <section class="p-3 bg-white rounded">
            <p class="text-white bg-green-500 rounded p-3 font-bold"><i class="fas fa-check"></i> No broken links found.</p>
        </section>
    @endif

    <section class="p-5 bg-white rounded">  
        <div class="py-2">
            <p class="font-bold">We checked {{$site->routes_count}} urls</p> 
            <p>This table contains all urls that we crawled.</p>        
        </div>
        <div class="overflow-x-auto mb-3">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
              <tr class="divide-x divide-gray-200">
                <th scope="col" class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pl-6">STATUS CODE</th>
                <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">INTERNAL LINKS</th>
                <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">FOUND ON</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse ($routes as $link)
                    <tr class="divide-x divide-gray-200">
                        <td class="whitespace-nowrap py-3 px-4 text-sm font-medium text-gray-900 sm:pl-3">{{ $link->http_code }}</td>
                        <td class="whitespace-nowrap py-3 px-4 text-sm text-purple-500 hover:text-purple-700"><a href="{{ $link->route }}">{{ $link->route }}</a></td>
                        <td class="whitespace-nowrap py-3 px-4 text-sm text-purple-500 hover:text-purple-700"><a href="{{ $link->found_on }}">{{ $link->found_on }}</a></td>
                    </tr>
                @empty
                <tr>
                    <td colspan="3">No links crawled yet.</td>
                </tr>
                @endforelse
            </tbody>
          </table>
        </div>
          {{ $routes->links() }}
    </section>
@endsection
<div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            @if(count($sites) > 0)
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
                        Schedulers
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Broken Links
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody>
                    
                    @foreach($sites as $site)
                        <!-- Odd row -->
                        <tr class="bg-white" wire:key="site-{{ $site->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-400">
                                <a href="{{ route('sites.show', ['site' => $site->id]) }}">{{$site->name ?? $site->url}}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class=" flex items-center">
                                    <a href="{{ route('sites.show', ['site' => $site->id]) }}" data-tippy-content="See uptime page">
                                        @livewire('status-bubble', [
                                            'status' => $site->getStatus()->label(),
                                            'success' => ['message' => 'Up'],
                                            'error' => ['message' => 'Down']
                                        ], key($site->id))
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class=" flex items-center">
                                    <a href="{{ route('site.ssl-certificate-health', ['site' => $site->id]) }}" data-tippy-content="See certificate health">
                                        @livewire('status-bubble', [
                                            'status' => $site->getSslCertificateStatus()->label(),
                                            'success' => ['message' => 'Valid'],
                                            'error' => ['message' => 'Expired'],
                                            'info' => ['message' => 'About expire']
                                        ], key($site->id))
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class=" flex items-center">
                                    <a href="{{ route('schedulers.index', ['site' => $site->id]) }}" data-tippy-content="See schedulers page">
                                        @livewire('status-bubble', [
                                            'status' => $site->hasSchedulers()->label(),
                                            'success' => ['message' => 'Monitoring'],
                                            'error' => ['message' => 'Not Registered']
                                        ], key($site->id))
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 flex items-center">
                                <a href="{{ route('sites.broken-links', ['site' => $site->id]) }}"  data-tippy-content="See broken links page">
                                    @livewire('status-bubble', [
                                        'status' => $site->brokenLinksStatus()->label(),
                                        'success' => ['message' => 'Not found'],
                                        'error' => ['message' => 'Found']
                                    ], key($site->id))
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <button class="text-green-500 hover:text-green-600" 
                                        wire:click="dispatchUptimeEvent({{$site->id}})"
                                        data-tippy-content="Run Uptime Job Now"><i class="fa-solid fa-play"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table> 
            @else
            <div class="flex flex-col items-center justify-center p-3">
                <i class="fa-solid fa-globe text-5xl"></i>
                <p class="mt-2">No monitors registered yet.</p>
            </div>
            @endif
        </div>
        <div class="my-5">
            {{ $sites->links() }}
        </div>
    </div>
</div>
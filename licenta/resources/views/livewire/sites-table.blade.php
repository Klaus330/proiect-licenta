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
                    
                    @forelse($sites as $site)
                        <!-- Odd row -->
                        <tr class="bg-white" wire:key="{{ $site->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-400">
                                <a href="{{ route('sites.show', ['site' => $site->id]) }}">{{$site->name ?? $site->url}}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class=" flex items-center">
                                    <a href="{{ route('sites.show', ['site' => $site->id]) }}">
                                        @livewire('status-bubble', [
                                            'status' => $site->getStatus()->label(),
                                            'success' => ['message' => 'Up'],
                                            'error' => ['message' => 'Down']
                                        ])
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class=" flex items-center">
                                    <a href="{{ route('site.ssl-certificate-health', ['site' => $site->id]) }}">
                                        @livewire('status-bubble', [
                                            'status' => $site->getSslCertificateStatus()->label(),
                                            'success' => ['message' => 'Valid'],
                                            'error' => ['message' => 'Expired'],
                                            'info' => ['message' => 'About expire']
                                        ])
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class=" flex items-center">
                                    <a href="{{ route('schedulers.index', ['site' => $site->id]) }}">
                                        @livewire('status-bubble', [
                                            'status' => $site->hasSchedulers()->label(),
                                            'success' => ['message' => 'Monitoring'],
                                            'error' => ['message' => 'Not Registered']
                                        ])
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 flex items-center">
                                <a href="{{ route('sites.broken-links', ['site' => $site->id]) }}">
                                    @livewire('status-bubble', [
                                        'status' => $site->brokenLinksStatus()->label(),
                                        'success' => ['message' => 'Not found'],
                                        'error' => ['message' => 'Found']
                                    ])
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <button class="text-green-500 hover:text-green-600" wire:click="dispatchUptimeEvent({{$site->id}})"><i class="fa-solid fa-play"></i></button>
                            </td>
                        </tr>
                    @empty
                        <div>
                            You have no sites added yet.
                        </div>
                    @endforelse
                </tbody>
            </table>            
        </div>
        <div class="my-5">
            {{ $sites->links() }}
        </div>
    </div>
</div>
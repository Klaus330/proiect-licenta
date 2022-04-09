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
                        Content
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
                                    @livewire('status-bubble', [
                                        'status' => $site->getStatus(),
                                        'success' => ['message' => 'Up'],
                                        'failed' => ['message' => 'Down']
                                    ])
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class=" flex items-center">
                                    @livewire('status-bubble', [
                                        'status' => $site->getSslCertificateStatus(),
                                        'success' => ['message' => 'Valid'],
                                        'failed' => ['message' => 'Expired']
                                    ])
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class=" flex items-center">
                                    @livewire('status-bubble', [
                                        'status' => $site->hasSchedulers(),
                                        'success' => ['message' => 'Monitoring'],
                                        'failed' => ['message' => 'Not Registered']
                                    ])
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 flex items-center">
                                {{-- <status status="{{ $site->status }}" success-message="{{ucfirst($site->status)}}"></status> --}}
                                {{-- @livewire('status-bubble', ['status' => $site->status]) --}}
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
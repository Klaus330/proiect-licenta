@if (count($latestStats) > 0)
@php
    $lastStats = $latestStats->first();    
@endphp
    <div class="w-full h-full">
        <a href="{{ route('sites.show', ['site' => $site->id]) }}">
            <div class="h-full">
                <div class="w-full bg-white shadow-xl rounded-lg h-full">
                    <div class="mb-2 border-b border-gray-300 p-4">
                        <span class="md:text-sm lg:text-lg font-bold whitespace-pre-wrap">{{ $site->name }}</span>
                    </div>
                    <div class="flex flex-col px-4 py-2">
                        <div class="grid grid-cols-2">
                            <div class="flex items-start flex-col">
                                <span class="text-gray-400 text-sm font-light">Performance</span>
                                <span class="font-semibold text-sm"> {{ $lastStats->duration ?? '--' }} ms</span>
                            </div>
                            <div class="flex items-start flex-col">
                                <span class="text-gray-400 text-sm font-light">Status</span>
                                <span class="font-semibold text-sm">
                                    {{ $lastStats->reason_phrase ?? '--' }}</span>
                            </div>
                            <div class="flex items-start flex-col">
                                <span class="text-gray-400 text-sm font-light">Last check</span>
                                <span class="font-semibold text-xs">
                                    {{ $lastStats->ended_at->diffForHumans() ?? '--' }}</span>
                            </div>
                            <div class="flex items-start flex-col">
                                <span class="text-gray-400 text-sm font-light">Last incident</span>
                                @if ($lastIncident)
                                    <span class="font-semibold text-sm">
                                        {{ $lastIncident->ended_at->diffForHumans() }}</span>
                                @else
                                    <span class="font-semibold text-sm">--</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-content-between w-full rounded max-h-9">
                        <div class="pr-1 flex-1 grid-cols-12 gap-0.5 flex">
                            @for ($i = 30; $i > 0; $i--)
                                @if (!isset($latestStats[$i]))
                                    <div class="w-full h-full bg-gray-200" title="No checks performed" data-tippy-content="No checks performed"></div>
                                @elseif($latestStats[$i]->successful())
                                    <div class="w-full h-full bg-green-300"
                                        title="{{ $latestStats[$i]->created_at->toFormattedDateString() }}" data-tippy-content="{{ $latestStats[$i]->created_at->toFormattedDateString() }}"></div>
                                @else
                                    <div class="w-full h-full bg-red-300"
                                        title="{{ $latestStats[$i]->created_at->toFormattedDateString() }}" data-tippy-content="{{ $latestStats[$i]->created_at->toFormattedDateString() }}"></div>
                                @endif
                            @endfor
                        </div>
                        @if(isset($latestStats[0]))
                            <div
                                class="flex items-center justify-center rounded-br {{ $latestStats[0]->successful() ? 'bg-green-300' : 'bg-red-300' }}">
                                <span class="text-xs p-0.5 px-1.5"
                                    title="{{ $latestStats[0]->created_at->toFormattedDateString() }}" 
                                    data-tippy-content="{{ $latestStats[0]->created_at->toFormattedDateString() }}">{{ $latestStats[0]->successful() ? 'Healthy' : 'Down' }}</span>
                            </div>
                        @else
                            <div
                                class="flex items-center justify-center rounded-br bg-gray-200">
                                <span class="text-xs p-0.5 px-1.5">No checks</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    </div>
@endif

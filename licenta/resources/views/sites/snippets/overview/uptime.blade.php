<div class="w-full">
    @if(count($latestStats) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3">
        <div class="w-full bg-white shadow-xl rounded-lg">
            <div class="mb-2 border-b border-gray-300 p-4">
                <span class="md:text-sm lg:text-lg font-bold whitespace-pre-wrap">{{ $site->name }}</span>
            </div>
            <div class="flex flex-col px-4 py-2">
                <div class="grid grid-cols-2">
                    <div class="flex items-start flex-col">
                        <span class="text-gray-400 text-sm font-light">Performance</span>
                        <span class="font-semibold text-sm"> {{ $latestStats[0]->duration ?? '--' }} ms</span>
                    </div>
                    <div class="flex items-start flex-col">
                        <span class="text-gray-400 text-sm font-light">Status</span>
                        <span class="font-semibold text-sm"> {{ $latestStats[0]->reason_phrase ?? '--' }}</span>
                    </div>
                    <div class="flex items-start flex-col">
                        <span class="text-gray-400 text-sm font-light">Last check</span>
                        <span
                            class="font-semibold text-xs"> {{ $latestStats[0]->ended_at->diffForHumans() ?? '--' }}</span>
                    </div>
                    <div class="flex items-start flex-col">
                        <span class="text-gray-400 text-sm font-light">Last incident</span>
                        @if($lastIncident)
                            <span class="font-semibold text-sm"> {{  $lastIncident->ended_at->diffForHumans() }}</span>
                        @else
                            <span class="font-semibold text-sm">--</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex justify-content-between w-full rounded max-h-9">
                <div class="pr-1 flex-1 grid-cols-12 gap-0.5 flex">
                    @for ($i = 30; $i > 0 ; $i--)
                        @if(!isset($latestStats[$i]))
                            <div class="w-full h-full bg-gray-200" title="No checks performed"></div>    
                        @elseif($latestStats[$i]->successful())
                            <div class="w-full h-full bg-green-300"  title="{{$latestStats[$i]->created_at->toFormattedDateString()}}"></div>
                        @else
                            <div class="w-full h-full bg-red-300"  title="{{$latestStats[$i]->created_at->toFormattedDateString()}}"></div>
                        @endif
                    @endfor
                </div>
                <div class="flex items-center justify-center rounded-br {{ $latestStats[0]->successful() ? 'bg-green-300' : 'bg-red-300'}}">
                    <span class="text-xs p-0.5 px-1.5" title="{{$latestStats[0]->created_at->toFormattedDateString()}}">{{ $latestStats[0]->successful() ? 'Healthy' : 'Down'}}</span>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
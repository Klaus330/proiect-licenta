@if ($site->hasSslCertificate())
    @php
        $ssl = $site->sslCertificate;
    @endphp
    <div class="w-full">
        <div class="h-full">
            <div class="w-full bg-white shadow-xl rounded-lg h-full">
                <div class="mb-2 border-b border-gray-300 p-4">
                    <span class="md:text-sm lg:text-lg font-bold whitespace-pre-wrap">Ssl Certificate</span>
                </div>
                <div class="flex flex-col px-4 py-2">
                    <div class="grid grid-cols-2 gap-y-3">
                        <div class="flex items-start flex-col">
                            <span class="text-gray-400 text-sm font-light">Valid From</span>
                            <span class="font-semibold text-sm"> {{ $ssl->validFrom->toFormattedDateString() }}</span>
                        </div>
                        <div class="flex items-start flex-col">
                            <span class="text-gray-400 text-sm font-light">ValidTo</span>
                            <span class="font-semibold text-sm"> {{ $ssl->validTo->toFormattedDateString() }}</span>
                        </div>
                        <div class="flex items-start flex-col">
                            <span class="text-gray-400 text-sm font-light">Alert in advance</span>
                            <span class="font-semibold text-xs">
                                {{ $ssl->expires }} days</span>
                        </div>
                        <div class="flex items-start flex-col">
                            <span class="text-gray-400 text-sm font-light">Validity</span>
                            <span class="font-semibold text-xs">
                                {{ now()->diffInDays($site->sslCertificate->validFrom) }} days remaining</span>
                        </div>
                    </div>
                </div>
                {{-- <div class="flex justify-content-between w-full rounded max-h-9">
                    <div class="pr-1 flex-1 grid-cols-12 gap-0.5 flex">
                        @for ($i = 30; $i > 0; $i--)
                            @if (!isset($latestStats[$i]))
                                <div class="w-full h-full bg-gray-200" title="No checks performed"></div>
                            @elseif($latestStats[$i]->successful())
                                <div class="w-full h-full bg-green-300"
                                    title="{{ $latestStats[$i]->created_at->toFormattedDateString() }}"></div>
                            @else
                                <div class="w-full h-full bg-red-300"
                                    title="{{ $latestStats[$i]->created_at->toFormattedDateString() }}"></div>
                            @endif
                        @endfor
                    </div>
                    <div
                        class="flex items-center justify-center rounded-br {{ $latestStats[0]->successful() ? 'bg-green-300' : 'bg-red-300' }}">
                        <span class="text-xs p-0.5 px-1.5"
                            title="{{ $latestStats[0]->created_at->toFormattedDateString() }}">{{ $latestStats[0]->successful() ? 'Healthy' : 'Down' }}</span>
                    </div>
                </div>
            </div> --}}
            </div>
        </div>
@endif

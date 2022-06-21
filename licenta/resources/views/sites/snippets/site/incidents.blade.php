<section class="mt-3">
    <h2 class="text-lg font-semibold">Last incidents</h2>
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="flex flex-col">
        <div class="-my-1 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-1 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-gray-50 p-3 rounded">
                    <table class="w-full table-auto border-separate border-spacing-x-4">
                        <thead>
                            <th class="text-left">Date</th>
                            <th class="text-left">Code</th>
                            <th class="text-left">Method</th>
                            <th class="text-left">Checked Url</th>
                            <th class="text-left">Duration</th>
                        </thead>
                        <tbody class="w-full">
                            @forelse ($lastIncidents as $incident)
                            <tr class="border-b  border-dashed border-gray-200 ">
                                <td class="py-4 whitespace-nowrap text-sm text-gray-900 px-2 md:px-0">
                                    {{ $incident->ended_at->toDayDateTimeString() }}
                                </td>
                                <td class="text-left">
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-red-100 text-red-800">
                                        {{ $incident->http_code }}
                                    </span>
                                </td>
                                <td class="px-2 md:px-0">
                                    {{ $site->verb }}
                                </td>
                                <td class="py-4 whitespace-nowrap text-sm text-gray-500 px-2 md:px-0">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i> <a href="{{$site->url}}" target="_blank" class="text-black">{{$site->url}}</a>
                                </td>
                                <td>
                                    @php
                                        $lastSuccessfulStats = $site->stats()->where('ended_at', '>',$incident->ended_at)->where('http_code', 'like', '2__')->get()->last();
                                    @endphp
                                    @if ($lastSuccessfulStats)
                                        {{ $incident->ended_at->diffInSeconds($lastSuccessfulStats->ended_at) }} sec.
                                    @else
                                        Still Down!
                                    @endif
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="3"> No incidents registered.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
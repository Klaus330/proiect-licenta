<section class="mt-3">
    <h2 class="text-lg font-semibold">Last incidents</h2>
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="flex flex-col">
        <div class="-my-1 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-1 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-gray-50 p-3 rounded">
                    <table class="w-full table-auto">
                        <thead>
                            <th class="text-left">Date</th>
                            <th class="text-left">Method</th>
                            <th class="text-left">Checked Url</th>
                        </thead>
                        <tbody class="w-full">
                            @forelse ($lastIncidents as $incident)
                            <tr class="border-b  border-dashed border-gray-200 ">
                                <td class="py-4 whitespace-nowrap text-sm text-gray-900 px-2 md:px-0">
                                    {{ $incident->ended_at->toDayDateTimeString() }}
                                </td>
                                <td class="px-2 md:px-0">
                                    {{ $site->verb }}
                                </td>
                                <td class="py-4 whitespace-nowrap text-sm text-gray-500 px-2 md:px-0">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i> <a href="{{$site->url}}" target="_blank" class="text-black">{{$site->url}}</a>
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
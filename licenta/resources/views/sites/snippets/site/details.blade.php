<section class="my-5">
    <h2 class="text-lg font-semibold">Check details</h2>
    <div class="flex flex-col">
        <div class="-my-1 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-1 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="w-full">
                        <thead></thead>
                        <tbody class="w-full">
                        <tr class="border-b  border-dashed border-gray-200 ">
                            <td class="py-4 whitespace-nowrap text-sm text-gray-900">
                                Started at
                            </td>
                            <td class="py-4 whitespace-nowrap text-sm text-gray-500">
                                {{$stats->started_at}}
                            </td>
                        </tr>
                        <tr class="border-b  border-dashed border-gray-200 ">
                            <td class="py-4 whitespace-nowrap text-sm text-gray-900">
                                Ended at
                            </td>
                            <td class="py-4 whitespace-nowrap text-sm text-gray-500">
                                {{$stats->ended_at}}
                            </td>
                        </tr>
                        <tr class="border-b  border-dashed border-gray-200 ">
                            <td class="py-4 whitespace-nowrap text-sm text-gray-900">
                                Duration
                            </td>
                            <td class="py-4 whitespace-nowrap text-sm text-gray-500">
                                {{$stats->getFormatedDuration()}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
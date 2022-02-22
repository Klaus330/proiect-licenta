
<div>
    <table class="w-full">
        <thead>
        <tr class="border-b  border-dashed border-gray-200 ">
            <th scope="col" class="text-sm text-gray-400 font-medium">
                Name
            </th>
            <th scope="col" class="text-sm text-gray-400 font-medium">
                Status
            </th>
            <th scope="col" class="text-sm text-gray-400 font-medium">
                Last Expected Run
            </th>
            <th scope="col" class="text-sm text-gray-400 font-medium">
                Frequency
            </th>
        </tr>
        </thead>
        <tbody class="w-full">
            @forelse($schedulers as $scheduler)
                @php
                    $cronExpression = new \Cron\CronExpression($scheduler->cronExpression);
                @endphp
                <tr  class="border-b  border-dashed border-gray-200 ">
                    <td  class="p-3 whitespace-nowrap text-sm text-indigo-500 text-center font-semibold">
                        <a href="{{ route('schedulers.show', ['site' => $site->id, 'scheduler' => $scheduler->id]) }}" class="hover:underline">{{$scheduler->name}}</a>
                    </td>
                    <td class="p-3 whitespace-nowrap text-sm text-gray-500 text-center">
                        @livewire('status-bubble', ['status' => $scheduler->getStatus()])
                    </td>
                    <td class="p-3 whitespace-nowrap text-sm text-gray-500 text-center flex flex-col break-words">
                        <span>{{(new \Carbon\Carbon($cronExpression ->getPreviousRunDate()->getTimestamp()))->diffForHumans()}}</span>
                        <span class="text-xs">{{(new \Carbon\Carbon($cronExpression ->getPreviousRunDate()->getTimestamp()))->toDateTimeString()}}</span>
                    </td>
                    <td  class="p-3 whitespace-nowrap text-sm text-gray-500 text-center">
                        {{\Lorisleiva\CronTranslator\CronTranslator::translate($scheduler->cronExpression)}}
                    </td>
                </tr>
            @empty
                <p>No schedulers added.</p>
            @endforelse
        </tbody>
    </table>

     {{ $schedulers->links() }}
</div>

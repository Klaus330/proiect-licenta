
<div class="overflow-x-auto">
    <table class="w-full">
        <thead>
        <tr class="border-b  border-dashed border-gray-200">
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
            <th>

            </th>
        </tr>
        </thead>
        <tbody class="w-full">
            @forelse($schedulers as $scheduler)
                @php
                    $cronExpression = new \Cron\CronExpression($scheduler->cronExpression);
                @endphp
                <tr class="border-b border-dashed border-gray-200 hover:bg-gray-200 cursor-pointer" 
                    wire:key="{{$scheduler->id}}"
                    x-data @click="window.location.href='{{ route('schedulers.show', ['site' => $site->id, 'scheduler' => $scheduler->id]) }}'">
                    <td  class="py-3 whitespace-nowrap text-sm text-indigo-500 text-center font-semibold">
                        <a href="{{ route('schedulers.show', ['site' => $site->id, 'scheduler' => $scheduler->id]) }}" class="hover:underline">{{ $scheduler->name }}</a>
                    </td>
                    <td class="py-3 whitespace-nowrap text-sm text-gray-500 text-center">
                        @livewire('status-bubble', ['status' => $scheduler->getStatus()->label()])
                    </td>
                    <td class="py-3 whitespace-nowrap text-sm text-gray-500 text-center flex flex-col break-words">
                        <span>{{( new \Carbon\Carbon($cronExpression ->getPreviousRunDate()->getTimestamp()))->diffForHumans() }}</span>
                        <span class="text-xs">{{ (new \Carbon\Carbon($cronExpression->getPreviousRunDate()->getTimestamp()))->toDateTimeString() }}</span>
                    </td>
                    <td  class="py-3 whitespace-nowrap text-sm text-gray-500 text-center">
                        {{ \Lorisleiva\CronTranslator\CronTranslator::translate($scheduler->cronExpression) }}
                    </td>
                    <td>
                        <div class="flex gap-2 items-center h-full">
                            <a href="#" class="bg-indigo-600 hover:bg-indigo-700 rounded p-2 text-white text-xs" @click.stop.prevent.prefetch="$wire.emit('updateScheduler', {'scheduler': {{ $scheduler->id }}})" ><i class="fas fa-edit"></i></a>
                            <form action="#" method="POST" @submit.prevent="window.dispatchEvent(new CustomEvent('showmodal', {detail: {
                                    title: 'Are you sure you want to delete this scheduler?',
                                    body: 'This action is ireversible',
                                    action: 'deleteScheduler',
                                    submitText: 'Delete',
                                    cancelText: 'Cancel',
                                    options: {schedulerId: {{ $scheduler->id }}}
                                }}))">
                                <button class="bg-red-600 hover:bg-red-700 rounded p-2 text-white text-xs" @click.stop>
                                        <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            <a href="{{ route('schedulers.settings', ['site' => $site->id, 'scheduler' => $scheduler->id]) }}" 
                               class="bg-white-600 hover:bg-white-700 border border-gray-600 hover:border-gray-800 rounded p-2 text-gray-600 text-xs">
                                <i class="fas fa-cog"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                   <td colspan="5">
                       <div class="flex items-center justify-center flex-col gap-2 py-6 text-gray-800">
                        <i class="fas fa-clock text-5xl"></i> No schedulers added.
                       </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
     {{ $schedulers->links() }}
</div>

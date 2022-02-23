<div class="flex flex-col w-full border-t border-gray-300 mt-5">
    <form action="#" method="POST" wire:submit.prevent="createScheduler">
        @csrf
        <div class="shadow sm:rounded-md sm:overflow-hidden">
            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{$mode === 'edit' ? 'Edit scheduler' : 'Create a new scheduler to this site'}}</h3>
                    <p class="mt-1 text-sm text-gray-500"> We will make an HTTP request to your Target URL according to
                        your schedule. No more working with servers. Learn more about our scheduler.</p>
                </div>

                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-3 sm:col-span-3">
                        <label for="name" class="block text-sm font-medium text-gray-700">Scheduler Name:</label>
                        <input type="text" name="name" 
                            id="name" wire:model.defer="scheduler.name" autocomplete="name" value="{{ $scheduler->name }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="col-span-3 sm:col-span-3">
                        <label for="schedulerType" class="block text-sm font-medium text-gray-700">Scheduler Type:</label>
                        <select id="schedulerType" name="schedulerType" autocomplete="schedulerType" wire:model="schedulerType"
                            class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="cron">Cron</option>
                            <option value="interval">Interval</option>
                        </select>
                    </div>

                    @switch($schedulerType)
                        @case('cron')
                            <div class="col-span-3 sm:col-span-3">
                                <label for="cronExpression" class="block text-sm font-medium text-gray-700">Cron Expression:</label>
                                <input type="text" name="scheduler.cronExpression" id="cronExpression" wire:model.defer="scheduler.cronExpression" autocomplete="cronExpression"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            @break
                        @case('interval')
                            <div class="col-span-3 sm:col-span-3">
                                <label for="interval" class="block text-sm font-medium text-gray-700">Interval:</label>
                                <select id="interval" name="interval" autocomplete="interval" wire:model.defer="interval"
                                    class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="60">Every minute</option>
                                    <option value="300">Every 5 min</option>
                                    <option value="3600">Hourly</option>
                                </select>
                            </div>
                            @break
                    @endswitch

                    <div class="col-span-3 sm:col-span-3">
                        <label for="method" class="block text-sm font-medium text-gray-700">Method:</label>
                        <select id="method" name="method" autocomplete="country-name" wire:model.defer="method"
                            class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="get"> GET</option>
                            <option value="post">POST</option>
                            <option value="head">HEAD</option>
                            <option value="put"> PUT</option>
                        </select>
                    </div>

                    <div class="col-span-3 sm:col-span-3">
                        <label for="schedulerEndpoint" class="block text-sm font-medium text-gray-700">Endpoint:</label>
                        <input type="text" name="scheduler.endpoint" id="schedulerEndpoint" wire:model.defer="scheduler.endpoint" autocomplete="schedulerEndpoint"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <x-jet-validation-errors class="mt-4" />
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    @if($mode === 'edit')
                        <button

                            type="button"
                            wire:click="resetForm"
                            class="bg-white border border-gray-400 hover:border-gray-600 rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-gray-500 hover:bg-white hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Clear
                        </button>
                    @endif
                    <button type="submit"
                        class="ml-4 bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{$mode === 'edit' ? 'Save' : 'Create'}}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

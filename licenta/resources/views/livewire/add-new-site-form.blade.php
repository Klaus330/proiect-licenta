<div class="mt-10 mb-10 px-4">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Let's see your website's health</h3>
                <p class="mt-1 text-sm text-gray-600">
                    We will check all the essential health parameters of your website.
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2  shadow-lg">
            <form action="{{ route('sites.store') }}" method="POST" wire:submit.prevent="addNewSite">
                @csrf

                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-4">
                                <label for="website" class="block text-sm font-medium text-gray-700">
                                    Your Website
                                </label>

                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="text" name="url" id="website"
                                           wire:model="url" 
                                           class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                           placeholder="https://www.example.com"
                                    >
                                </div>
                                <x-jet-validation-errors class="mt-4" />
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Add site
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
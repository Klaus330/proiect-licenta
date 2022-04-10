<div x-data="{ title:'title', body:'body', submitText:'Submit', hasCancel: false, cancelText: 'Cancel', showModal: false, action: 'noAction', options:{}}" 
    x-show="showModal"
    @showmodal.window="
        title = $event.detail.title;
        body = $event.detail.body;
        submitText = $event.detail.submitText;
        cancelText = $event.detail.cancelText;
        action = $event.detail.action;
        options = $event.detail.options;
        hasCancel = $event.detail.hasOwnProperty('cancelText');
        showModal = true;
    "
    class="h-screen w-screen absolute top-0 left-0 bg-gray-800/50" style="display: none;">
    <div class="w-2/5 mx-auto mt-10 bg-white rounded p-3">
        <div class="header border-b border-gray-300 flex justify-between items-center pb-2">
            <h4 x-text="title">Header</h4>
            <span @click="showModal= false" class="cursor-pointer"><i class="fas fa-times"></i></span>
        </div>
        <div class="body py-2 border-b border-gray-300 my-2" x-show="body !== ''">
            <p  x-text="body">Body</p>
        </div>
        <div class="footer w-full flex justify-end items-center gap-3 py-2"> 
            <button 
                x-show="hasCancel"
                x-text="cancelText"
                @click="showModal= false"
                class="bg-white border border-gray-400 hover:border-gray-600 rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-gray-500 hover:bg-white hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </button>
            <button 
                x-text="submitText"
                @click.stop.prevent="Livewire.emit(action, {payload: options})"
                class="bg-indigo-600 border border-indigo-400 hover:border-indigo-600 rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Submit
            </button>
        </div>
    </div>
</div>
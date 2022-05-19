@extends('layouts.dashboard')

@section('basic_head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/9.7.4/jsoneditor.css" rel="stylesheet" type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/9.7.4/jsoneditor.min.js"></script>
@endsection

@section('dashboard-content')
    <form class="p-5 bg-white rounded" 
          x-data="settingsForm()" @submit.prevent="saveSettings"
          action="{{route('schedulers.settings.save', ['site' => $site->id, 'scheduler' => $scheduler->id])}}" method="POST">
        @csrf
        <h2 class="font-semibold text-2xl">Scheduler "{{ $scheduler->name }}" settings</h2>
        
        <x-jet-validation-errors class="mb-4" />

        <div class="mt-4">
            <h3 class="font-semibold text-xl mb-2  border-b border-gray-300 py-2">Authentication</h3>
            <div class="px-3 ">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="needs_auth" name="needs_auth" type="checkbox" x-model="needsAuth"
                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{$scheduler->needs_auth ? 'checked' : ''}}>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="needs_auth" class="font-medium text-gray-700">Needs Authentication</label>
                        <p class="text-gray-500">The scheduler need to have credentials to access that page</p>
                    </div>


                </div>
                <div x-show="needsAuth" class="w-1/2 mt-3">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="auth-route" class="block text-sm font-medium text-gray-700">Auth Route</label>
                        <input type="text" name="auth_route" id="auth-route" autocomplete="given-name" value="{{$scheduler->auth_route}}"
                            x-model="authRoute" placeholder="Enter the route to authenticate"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div class="col-span-6 sm:col-span-3 mt-3">
                        <div>
                            <h3 class="text-lg">User credentials</h3>
                            <p class="text-gray-600 text-sm">These credentials will be used by the scheduler to log in.</p>
                        </div>
                        @forelse ($scheduler->auth_payload as $key => $value)
                            <div class="flex gap-2 w-full items-end">
                                <div>
                                    <label for="auth-key" class="block text-sm font-medium text-gray-700 mt-4">Key</label>
                                    <input type="text" name="authKeys[]" id="auth-key" autocomplete="given-name" value="{{$key}}"
                                        placeholder="Key"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="auth-value" class="block text-sm font-medium text-gray-700 mt-4">Value</label>
                                    <input type="text" name="authValues[]" id="auth-value" autocomplete="given-name" value="{{$value}}"
                                        placeholder="Value"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <button type="button" @click="$el.parentElement.parentElement.remove();" class="bg-red-400 rounded p-2 text-white"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        @empty
                            <div class="flex gap-2 w-full items-end">
                                <div>
                                    <label for="auth-key" class="block text-sm font-medium text-gray-700 mt-4">Key</label>
                                    <input type="text" name="authKeys[]" id="auth-key" autocomplete="given-name" 
                                        placeholder="Key"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="auth-value" class="block text-sm font-medium text-gray-700 mt-4">Value</label>
                                    <input type="text" name="authValues[]" id="auth-value" autocomplete="given-name"
                                        placeholder="Value"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <button type="button" @click="$el.parentElement.parentElement.remove();" class="bg-red-400 rounded p-2 text-white"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        @endforelse
                       
                        <span id="credentials-target"></span>
                    </div>
                    <div class="mt-3">
                        <button type="button" class="text-white bg-indigo-600 hover:bg-indigo-700 rounded px-4 py-2" @click="addNewParam()">Add</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 border-t border-gray-300 py-3">
            <div class="mb-4">
                <h2 class="font-semibold text-xl">Scheduler Payload</h2>
                <p class="text-gray-400 text-sm">This payload will be send in the request body.</p>
            </div>
            <div id="jsoneditor" class="w-full" style="height: 400px;"></div>
        </div>

        <div class="mt-10 flex justify-end items-center">
            <button class="text-white bg-indigo-600 hover:bg-indigo-700 rounded px-4 py-2">Save changes</button>
        </div>
    </form>
@endsection


@section('dashboard-script')
    <script>
        const container = document.getElementById("jsoneditor")
        const editor = new JSONEditor(container, {})
        editor.set(@json($scheduler->payload))

        function settingsForm()
        {
            return {
                authRoute: @json($scheduler->auth_route),
                needsAuth: @json($scheduler->needs_auth),
                authKeys: [],
                authValues: [],
                saveSettings(event) {
                    let authKeys = []
                    let authValues = []

                    document.querySelectorAll('input[name="authKeys[]"]').forEach(el => {
                        authKeys.push(el.value)
                    })

                    document.querySelectorAll('input[name="authValues[]"]').forEach(el => {
                        authValues.push(el.value)
                    })  

                    const updatedJson = editor.get()

                    axios.post('{{ route('schedulers.settings.save', ['site' => $site->id, 'scheduler' => $scheduler->id]) }}',{
                        'auth_route': this.authRoute,
                        'authKeys': authKeys,
                        'authValues': authValues,
                        'needs_auth': this.needsAuth,
                        'payload': updatedJson
                    }).then((response) => {
                        toastr.success("Settings saved.")
                    }).catch((response) => {
                        toastr.error("Error saving settings.")
                    })
                },
                template:` <div class="flex gap-2 w-full items-end">
                            <div>
                                <label for="auth-route" class="block text-sm font-medium text-gray-700 mt-4">Key</label>
                                <input type="text" name="authKeys[]" id="auth-route" autocomplete="given-name"
                                    placeholder="Keys"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="auth-route" class="block text-sm font-medium text-gray-700 mt-4">Value</label>
                                <input type="text" name="authValues[]" id="auth-route" autocomplete="given-name"
                                    placeholder="Value"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <button type="button" @click="$el.parentElement.parentElement.remove()" class="bg-red-400 rounded p-2 text-white"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>`,
                addNewParam(){
                    let newParam = document.createElement('div');
                    newParam.innerHTML = this.template;
                    document.getElementById('credentials-target').appendChild(newParam);
                }
            };
        }
    </script>
@endsection
@extends('layouts.dashboard')

@section('basic_head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/9.7.4/jsoneditor.css" rel="stylesheet" type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/9.7.4/jsoneditor.min.js"></script>
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/basic.css" integrity="sha512-+Vla3mZvC+lQdBu1SKhXLCbzoNCl0hQ8GtCK8+4gOJS/PN9TTn0AO6SxlpX8p+5Zoumf1vXFyMlhpQtVD5+eSw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
@endsection

@section('dashboard-content')
    <form class="p-5 bg-white rounded my-5 " 
          x-data="settingsForm()" @submit.prevent="saveSettings()"
          x-init="$watch('needsAuth', (value) => {if(needsAuth){runsRemoteCode = false;} needsAuth = value;}); $watch('runsRemoteCode', (value) => {if(runsRemoteCode){needsAuth = false;} runsRemoteCode = value;});"
          action="{{route('schedulers.settings.save', ['site' => $site->id, 'scheduler' => $scheduler->id])}}" method="POST"  enctype="multipart/form-data">
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

        <div class="mt-10 border-t border-gray-300 py-3 px-3">
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="has_remote_code" name="has_remote_code" type="checkbox" x-model="runsRemoteCode"
                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{$scheduler->has_remote_code ? 'checked' : ''}}>
                </div>
                <div class="ml-3 text-sm">
                    <label for="has_remote_code" class="font-medium text-gray-700">Has Remote Code Execution</label>
                    <p class="text-gray-500">The scheduler will execute the code you provide</p>
                </div>
            </div>

            <div x-show="runsRemoteCode">
                @if($scheduler->has_remote_code)
                    <div class="relative">
                        <h3 class="font-semibold text-md my-2">Code preview:</h3>
                        
                        <div class="h-full w-full bg-white absolute top-0 left-0 flex items-center justify-center spinner">
                            <svg role="status" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"></path>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"></path>
                            </svg>
                        </div>
                        <div id="codepreview" style="max-height: 800px; overflow-y:auto;" class="my-4">
                        </div>
                    </div>
                @endif
                <div class="flex justify-start flex-col">
                    <label for="file">Upload a script:</label>
                    <input type="file" name="file"/>
                </div>
            </div>
            
            <div class="mt-4 border-t border-gray-300 py-3">
                <div class="mb-4">
                    <h2 class="font-semibold text-xl">Scheduler Payload</h2>
                    <p class="text-gray-400 text-sm">This payload will be send in the request body.</p>
                </div>
    
                <div id="jsoneditor" class="w-full" style="height: 400px;"></div>
            </div>
            
            <div class="mt-10 flex justify-end items-center bg-white">
                <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 rounded px-4 py-2">Save changes</button>
            </div>
        </div>
    </form>
@endsection


@section('dashboard-script')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js" integrity="sha512-oQq8uth41D+gIH/NJvSJvVB85MFk1eWpMK6glnkg6I7EdMqC1XVkW7RxLheXwmFdG03qScCM7gKS/Cx3FYt7Tg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
         let myDropzone = new Dropzone(".dropzone", { url: "/file/post"});
    </script> --}}
    <script src="https://unpkg.com/shiki"></script>
    <script>
        function highlightCode(code){
            document.querySelector('#codepreview pre')?.remove()
            document.querySelector('.spinner').style.display = 'flex'
            console.log('hit')
            shiki.getHighlighter({
                theme: 'nord'
            })
            .then(highlighter => {
                let highlightedCode = highlighter.codeToHtml(code, { lang: "javascript" })
                document.querySelector('#codepreview').innerHTML = highlightedCode
                document.querySelector('#codepreview pre').classList.add('p-3')
                document.querySelector('.spinner').style.display = 'none'
            }).catch(err => {
                console.error('here', err)
            })
        }

        @if($scheduler->has_remote_code)
            highlightCode(`{!!file_get_contents($scheduler->remote_code_path_with_filename)!!}`);
        @endif
        
        const container = document.getElementById("jsoneditor")
        const editor = new JSONEditor(container, {})
        editor.set(@json($scheduler->payload))

        function settingsForm()
        {
            return {
                authRoute: @json($scheduler->auth_route),
                needsAuth: @json($scheduler->needs_auth),
                runsRemoteCode: @json($scheduler->has_remote_code),
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
                    
                        let formData = new FormData();
                        let file = document.querySelector('input[type="file"]').files[0];
                        formData.append('file', file);
                        formData.append('scheduler', '{{ $scheduler->id }}');
                        formData.append('payload', updatedJson);
                        formData.append('authKeys[]', authKeys);
                        formData.append('authValues[]', authValues);
                        formData.append('needsAuth', this.needsAuth);
                        formData.append('hasRemoteCode', this.runsRemoteCode);
                        formData.append('authRoute', this.authRoute);

                        axios.post(
                            '{{ route('schedulers.settings.save', ['site' => $site->id, 'scheduler' => $scheduler->id]) }}',
                            formData
                        ).then((response) => {
                            toastr.success("Settings saved.")
                            console.log(response)
                            if(response.data['code'] != undefined)
                            {
                                highlightCode(response.data['code']);
                            }
                        }).catch((response) => {
                            console.log(response)
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
<section class="my-5">
    <h2 class="text-lg font-semibold">Check results</h2>
    <div class="flex flex-col">
        <div class="overflow-x-hidden">
            <div class="py-1 align-middle inline-block min-w-full">
                <div class="overflow-hidden w-full">
                    <div class="flex w-full flex-col">
                        <div
                            class="w-full flex border-b border-dashed border-gray-300 flex-col md:flex-row sm:items-center ">
                                        <span class="mr-20 py-4 whitespace-nowrap text-sm text-gray-900">
                                             Monitored from
                                        </span>
                            <span class="mr-20 py-4 whitespace-nowrap text-sm text-black">
                                            Frankfurt, Germany <span class="text-xs">192.168.0.1</span>
                                        </span>
                        </div>
                        <div
                            class="w-full flex flex-col md:flex-row sm:items-center border-b border-dashed border-gray-300 py-2">
                                        <span class="mr-20 py-4 whitespace-nowrap text-sm text-gray-900 py-2">
                                            HTTP status code
                                        </span>

                            <div class="shadow bg-gray-200 p-2 rounded text-black text-xs py-2">
                                {{$stats->scheme}}
                                /{{$stats->protocol_version}} {{$stats->http_code}} {{$stats->reason_phrase}}
                            </div>
                        </div>

                        <div
                            class="w-auto flex border-b border-dashed border-gray-300 flex-col md:flex-row sm:items-center ">
                                        <span class=" mr-10 py-4 whitespace-nowrap text-sm text-gray-900 py-2">
                                            HTTP response headers
                                        </span>

                            <div
                                class="shadow bg-gray-200 p-2 rounded text-black text-xs my-2 overflow-x-auto whitespace-pre w-full max-h-96">
                                @foreach(json_decode($stats->headers) as $headerTitle => $headerInfo)
                                    @foreach($headerInfo as $info)
                                        <p>{{$headerTitle}}: {{substr($info,0 ,50)}} {{ strlen($info) > 50 ? '...' : '' }}</p>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
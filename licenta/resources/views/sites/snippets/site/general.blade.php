<section class="mt-3">
    <h2 class="text-lg font-semibold">Monitoring details</h2>
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="flex flex-col">
        <div class="-my-1 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-1 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="w-full">
                        <thead></thead>
                        <tbody class="w-full">
                        <tr class="border-b  border-dashed border-gray-200 ">
                            <td class="py-4 whitespace-nowrap text-sm text-gray-900">
                                Monitored url
                            </td>
                            <td class="py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="" class="text-black">{{$site->url}}</a> <span class="text-xs">192.168.0.1</span>
                            </td>
                        </tr>
                        <tr class="border-b  border-dashed border-gray-200 ">
                            <td class="py-4 whitespace-nowrap text-sm text-gray-900 pr-10">
                                HTTP request
                            </td>
                            <td class="py-4 whitespace-nowrap text-sm text-gray-500">
                                <div
                                    class="shadow bg-gray-200 p-4 rounded text-black  w-11/12 overflow-y-auto">
                                                    <pre class="overflow-x-auto p-2 text-sm leading-3">
curl --location --include --request GET --compressed {{$site->url}} \
    -H "Accept-Encoding: gzip, deflate" \
    -H "User-Agent: Whoops.app (+https://whoops.app/docs/checks/uptime)"\
    -H "Accept: */*"
                                                    </pre>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
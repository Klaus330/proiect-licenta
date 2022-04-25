@extends('layouts.dashboard')

@section('dashboard-content')
    <section class="p-5 bg-white my-4">
        <div>
            <h1 class="text-2xl font-semibold">Ssl Certificate Health</h1>
        </div>
        @if ($site->hasSslCertificate())
            <section class="my-5">
                <h2 class="text-lg font-semibold">Certificate checks</h2>
                <div class="flex flex-col">
                    <div class="-my-1 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-1 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="overflow-hidden">
                                <table class="w-full">
                                    <thead></thead>
                                    <tbody class="w-full">
                                        <tr class="border-b  border-dashed border-gray-200 ">
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-900 w-3/12">
                                                Certificate is present
                                            </td>
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-500  w-1/12 px-3">
                                                {!! $site->hasSslCertificate() ? '<i class="fas fa-circle-check text-green-500"></i>' : '<i class="fa-solid fa-circle-exclamation text-red-500"></i>' !!}
                                            </td>
                                            <td class="w-8/12"></td>
                                        </tr>
                                        <tr class="border-b  border-dashed border-gray-200 ">
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-900 w-3/12">
                                                Will not expire in the next 14 days
                                            </td>
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-500  w-1/12 px-3">
                                                {!! $site->sslCertificate->validTo->gt(now()->addDays(14)) ? '<i class="fas fa-circle-check text-green-500"></i>' : '<i class="fa-solid fa-circle-exclamation text-red-500"></i>' !!}
                                            </td>
                                            <td class="w-8/12"></td>
                                        </tr>
                                        <tr class="border-b  border-dashed border-gray-200 ">
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-900 w-3/12">
                                                Has not expired
                                            </td>
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-500  w-1/12 px-3">
                                                {!! !$site->sslCertificate->hasExpired() ? '<i class="fas fa-circle-check text-green-500"></i>' : '<i class="fa-solid fa-circle-exclamation text-red-500"></i>' !!}
                                            </td>
                                            <td class="w-8/12"></td>
                                        </tr>
                                        <tr class="border-b  border-dashed border-gray-200 ">
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-900 w-3/12">
                                                Is not going to be revoked soon
                                            </td>
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-500  w-1/12 px-3">
                                                {!! $site->sslCertificate->validTo->gt(now()->addDays(7)) ? '<i class="fas fa-circle-check text-green-500"></i>' : '<i class="fa-solid fa-circle-exclamation text-red-500"></i>' !!}
                                            </td>
                                            <td class="w-8/12"></td>
                                        </tr>
                                        <tr class="border-b  border-dashed border-gray-200 ">
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-900 w-3/12">
                                                Uses a valid hashing algorithm
                                            </td>
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-500 w-1/12 px-3">
                                                {!! $site->sslCertificate->avoidsSha1Hash ? '<i class="fas fa-circle-check text-green-500"></i>' : '<i class="fa-solid fa-circle-exclamation text-red-500"></i>' !!}
                                            </td>
                                            <td class="w-8/12"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="my-5">
                <h2 class="text-lg font-semibold">Main certificate details</h2>
                <div class="flex flex-col">
                    <div class="-my-1 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-1 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="overflow-hidden">
                                <table class="w-full">
                                    <thead></thead>
                                    <tbody class="w-full">
                                        <tr class="border-b  border-dashed border-gray-200 ">
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-900 w-3/12">
                                                Issuer
                                            </td>
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-500 w-1/12 px-3">
                                                {{ $site->sslCertificate->issuer }}
                                            </td>
                                            <td class="w-8/12"></td>
                                        </tr>
                                        <tr class="border-b  border-dashed border-gray-200 ">
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-900 w-3/12">
                                                Valid From
                                            </td>
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-500 w-1/12 px-3">
                                                {{ $site->sslCertificate->validFrom->toDateTimeString() }}
                                            </td>
                                            <td class="w-8/12"></td>
                                        </tr>
                                        <tr class="border-b  border-dashed border-gray-200 ">
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-900 w-3/12">
                                                Valid To
                                            </td>
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-500 w-1/12 px-3">
                                                {{ $site->sslCertificate->validTo->toDateTimeString() }}
                                                ({{ now()->diffInDays($site->sslCertificate->validFrom) }} days remaining)
                                            </td>
                                            <td class="w-8/12"></td>
                                        </tr>
                                        <tr class="border-b  border-dashed border-gray-200 ">
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-900 w-3/12">
                                                Lifetime in days
                                            </td>
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-500 w-1/12 px-3">
                                                {{ $site->sslCertificate->validTo->diffInDays($site->sslCertificate->validFrom) }}
                                                days
                                            </td>
                                            <td class="w-8/12"></td>
                                        </tr>
                                        <tr class="border-b  border-dashed border-gray-200 ">
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-900 w-3/12">
                                                Valid for domains
                                            </td>
                                            <td
                                                class="py-4 whitespace-nowrap text-sm text-gray-500 w-1/12 px-3 flex flex-col justify-start">
                                                <span>{{ $site->sslCertificate->subject }}</span>
                                                <span>www.{{ $site->sslCertificate->subject }}</span>
                                            </td>
                                            <td class="w-8/12"></td>
                                        </tr>
                                        <tr class="border-b  border-dashed border-gray-200 ">
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-900 w-3/12">
                                                Avoids SHA1 hash
                                            </td>
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-500 w-1/12 px-3">
                                                {!! $site->sslCertificate->avoidsSha1Hash ? '<i class="fas fa-circle-check text-green-500"></i>' : '<i class="fa-solid fa-circle-exclamation text-red-500"></i>' !!}
                                            </td>
                                            <td class="w-8/12"></td>
                                        </tr>
                                        <tr class="border-b  border-dashed border-gray-200 ">
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-900 w-3/12">
                                                Certificate retrieved from
                                            </td>
                                            <td class="py-4 whitespace-nowrap text-sm text-gray-500 w-1/12 px-3">
                                                {{ $site->sslCertificate->ipAddress }}:443
                                            </td>
                                            <td class="w-8/12"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
          @else
          <p class="bg-yellow-400 rounded p-3 flex justify-between items-center"> We are still collecting data about your ssl certificate. <i class="fa-solid fa-circle-exclamation"></i></p>
        @endif
    </section>
@endsection

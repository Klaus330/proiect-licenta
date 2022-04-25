@extends('layouts.dashboard')

@section('basic_head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
@endsection

@section('dashboard-content')
<section class="p-5 bg-white mt-4 rounded">
    <div>
        <h1 class="text-2xl font-semibold">Performance</h1>
    </div>
    <div class="grid grid-cols-4 gap-3">
        <canvas id="performance_chart" width="1000" height="400"></canvas>
    </div>
</section>

<section class="p-5 bg-white my-4 rounded">
    <div class="mb-2">
        <h1 class="text-md font-semibold">Performance checks</h1>
    </div>
    <div class="flex flex-col">
        <div class="border-b border-dashed grid grid-cols-12 gap-3 p-2">
            <p class="text-sm text-gray-700 mr-10 col-span-3">Performance under 3500ms</p>
            <p class="text-sm text-gray-700 col-span-9">The time it takes to resolve the domain name to an IP address via DNS.</p>
        </div>
        <div class="border-b border-dashed grid grid-cols-12 gap-3 p-2">
            <p class="text-sm text-gray-700 mr-10 col-span-3">Performance change under 50%</p>
            <p class="text-sm text-gray-700 col-span-9">The time it takes to resolve the domain name to an IP address via DNS.</p>
        </div>
    </div>
</section>

<section class="p-5 bg-white my-4 rounded">
    <div class="mb-2">
        <h1 class="text-md font-semibold">What do these numbers mean?</h1>
    </div>
    <div class="flex flex-col">
        <div class="border-b border-dashed grid grid-cols-12 gap-3 p-2">
            <span class="inline-block h-4 w-8 mr-2 border col-span-1" style="background-color: rgba(255, 206, 86, 0.2); border-color: rgba(255, 206, 86, 1)"></span>
            <p class="text-sm text-gray-700 mr-10 col-span-2">DNS Lookup time</p>
            <p class="text-sm text-gray-700 col-span-9">The time it takes to resolve the domain name to an IP address via DNS.</p>
        </div>
        <div class="border-b border-dashed grid grid-cols-12 gap-3 p-2">
            <span class="inline-block h-4 w-8 mr-2 border col-span-1" style="background-color: rgba(255, 99, 132, 0.2); border-color: rgba(255, 99, 132, 1)"></span>
            <p class="text-sm text-gray-700 mr-10 col-span-2"> Remote server processing</p>
            <p class="text-sm text-gray-700 col-span-9">The time it took the server to process the request and start sending the first byte of the page.</p>
        </div>
        <div class="border-b border-dashed grid grid-cols-12 gap-3 p-2">
            <span class="inline-block h-4 w-8 mr-2 border col-span-1" style="background-color: rgba(54, 162, 235, 0.2); border-color: rgba(54, 162, 235, 1)"></span>
            <p class="text-sm text-gray-700 mr-10 col-span-2">TLS connection time</p>
            <p class="text-sm text-gray-700 col-span-9">The total time it took for the TLS handshake to complete (cipher negotiation & encryption).</p>
        </div>
        <div class="border-b border-dashed grid grid-cols-12 gap-3 p-2">
            <span class="inline-block h-4 w-8 mr-2 border col-span-1" style="background-color: rgba(255, 159, 64, 0.2); border-color: rgba(255, 159, 64, 1)"></span>
            <p class="text-sm text-gray-700 mr-10 col-span-2">Content download</p>
            <p class="text-sm text-gray-700 col-span-9">The time, in seconds, it took for the page to be downloaded.</p>
        </div>
        <div class="border-b border-dashed grid grid-cols-12 gap-3 p-2">
            <span class="inline-block h-4 w-8 mr-2 border col-span-1" style="background-color: rgba(75, 192, 192, 0.2); border-color: rgba(75, 192, 192, 1)"></span>
            <p class="text-sm text-gray-700 mr-10 col-span-2">Total Time</p>
            <p class="text-sm text-gray-700 col-span-9">The total time of the request.</p>
        </div>
    </div>
</section>

@endsection

@section('basic_scripts')
<script>
    const ctx = document.getElementById('performance_chart').getContext('2d');
    const performance_chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels:@json($data['dates']),
            datasets: [
            {
                label: 'DNS',
                data: {{json_encode($data['dns_lookup'])}},
                borderColor: ['rgba(255, 206, 86, 1)'],
                backgroundColor:['rgba(255, 206, 86, 0.1)'],
                borderWidth: 2,
            },
            {
                label: 'Content download',
                data: {{json_encode($data['content_download'])}},
                borderColor: [ 'rgba(255, 159, 64, 1)'],
                backgroundColor: [ 'rgba(255, 159, 64, 0.1)',],
                borderWidth: 2,
            },
            {
                label: 'Remote server processing',
                data: {{json_encode($data['transfer_time'])}},
                borderColor: [
                    'rgba(255, 99, 132, 1)'
                ],
                backgroundColor: ['rgba(255, 99, 132, 0.1)',],
                borderWidth: 2,
            },
            {
                label: 'TLS',
                data: {{json_encode($data['tls_time'])}},
                borderColor: ['rgba(54, 162, 235, 1)',],
                backgroundColor: ['rgba(54, 162, 235, 0.1)',],
                borderWidth: 2,
            },
            {
                label: 'Total Time',
                data: {{json_encode($data['total_time'])}},
                borderColor: ['rgba(75, 192, 192, 1)',],
                backgroundColor: ['rgba(75, 192, 192, 0.1)',],
                borderWidth: 2,
            },
        ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                },
                x: {
                    beginAtZero: true
                }
            },
            fill: true
        }
    });
    performance_chart.resize(600, 600);
    </script>
@endsection
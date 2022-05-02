@extends("layouts.dashboard")

@section('dashboard-content')
    <section class="p-5 bg-white rounded">       
        <div class="flex justify-end items-center border-b border-gray-300 py-2 overflow-auto gap-3 sm:justify-between">
            <h2 class="hidden sm:whitespace-nowrap sm:block">Scheduler: <b>{{ $scheduler->name }}</b></h2>
            <div class="flex gap-2 items-center justify-end flex-nowrap">
                <p class="flex gap-1 items-center">
                    <i class="fas fa-globe mr-1"></i>
                    {{ $scheduler->endpoint === "" ? '/' : $scheduler->endpoint }}
                </p>
                <p>
                    @livewire('status-bubble', ['status' => $scheduler->getStatus()->label()])
                </p>
                <p>
                    <i class="fa-solid fa-repeat mr-1"></i>
                    {{ \Lorisleiva\CronTranslator\CronTranslator::translate($scheduler->cronExpression) }}
                </p>
            </div>
        </div>
        <div>
            @forelse($stats as $statistics)
                <div x-data="{ show: false }" class="{{ $statistics->successful() ? 'bg-green-600' : 'bg-red-600' }} p-2 my-2 rounded text-white cursor-pointer" @click="show= !show">
                    <div class="flex w-full justify-between items-center">
                        <p>Started at: {{ $statistics->executed_at->toDayDateTimeString() }}</p>
                        <div class="flex gap-2">
                            <p>
                                Code: {{ $statistics->status_code }}
                            </p>
                            <p class="flex gap-2">
                                {{ $statistics->duration }} ms
                                <span><i class="fas" :class="show ? 'fa-caret-up' : 'fa-caret-down'"></i></span>
                            </p>
                        </div>
                    </div>
                    <div x-show="show" class="py-4 px-2 rounded" style="display: none;">
                        <p>Headers:</p>
                        <div class="overflow-auto bg-white text-black max-h-80 p-4 mb-5">
                            @forelse($statistics->headers as $headerName => $header)
                                <p class="whitespace-nowrap">
                                    <b>{{$headerName}}:</b>
                                    {{ $header[0] }}
                                </p>
                            @empty
                                <p>No headers present</p>
                            @endforelse
                        </div>
                        <p>Body:</p>
                        <div class="overflow-auto bg-white text-black max-h-80 p-4">
                            <pre>
                                <code>
                                    {{ $statistics->response_body }}
                                </code>
                            </pre>
                        </div>
                    </div>
                </div>
            @empty
                <p>We did not collect any information yet.</p>
            @endforelse

            {{ $stats->links() }}
        </div>
    </section>
@endsection
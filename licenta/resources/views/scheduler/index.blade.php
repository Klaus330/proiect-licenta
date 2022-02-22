@extends("layouts.dashboard")

@section('dashboard-content')
    <section class="p-5 bg-white rounded">       
            @livewire('schedulers-table', ['site' => $site])
        <div>
            @livewire('scheduler-form', ['site' => $site])
        </div>
    </section>
@endsection
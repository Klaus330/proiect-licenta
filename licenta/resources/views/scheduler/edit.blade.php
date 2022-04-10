@extends("layouts.dashboard")

@section('dashboard-content')
    <section class="p-5 bg-white rounded">       
        <div>
            @livewire('scheduler-form', ['site' => $site, 'scheduler' => $scheduler])
        </div>
    </section>
@endsection
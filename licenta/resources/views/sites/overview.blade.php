@extends('layouts.dashboard')

@section('dashboard-content')
<section class="p-5 bg-white mt-4">
    <div>
        <h1 class="text-2xl font-semibold">Overview</h1>
    </div>
    <div>
        @include('sites.snippets.overview.uptime')        
    </div>
</section>
@endsection
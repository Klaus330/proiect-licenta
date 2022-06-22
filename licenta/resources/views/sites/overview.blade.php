@extends('layouts.dashboard')

@section('dashboard-content')
<section class="p-5 bg-white mt-4">
    <div>
        <h1 class="text-2xl font-semibold">Overview</h1>
    </div>
    <div class="grid md:grid-cols-4 gap-3 sm: grid-cols-1">
        @include('sites.snippets.overview.uptime')
        @include('sites.snippets.overview.ssl')
    </div>
</section>
@endsection
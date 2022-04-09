<div class="w-full p-5 bg-white my-5 rounded flex justify-between overflow-auto gap-4">
    <div class="flex gap-4 items-center">
        <div>
            <a href="{{route('sites.index')}}" title="Back to sites" class="bg-white border py-1 px-2 text-sm border-gray-400 rounded text-gray-400 hover:text-gray-600 hover:border-gray-600"><i class="fas fa-arrow-left"></i></a>
        </div>
        <nav>
            <ul class="flex gap-4 justify-start items-center">
                <li class="text-gray-500 hover:border-gray-300 border-transparent border-b-2 {{ Request::is("sites/*") ? 'text-purple-500 border-purple-300' : '' }}">
                    <a href="{{ route('sites.show', ['site' => $site->id]) }}">
                        Uptime
                    </a>
                </li>
                <li class="text-gray-500  hover:border-gray-300 border-transparent border-b-2 {{ Request::is("site/*/overview") ? 'text-purple-500 border-purple-300' : '' }}">
                    <a href="{{ route('sites.overview', ['site' => $site->id]) }}">
                        Overview
                    </a>
                </li>
                <li class="text-gray-500  hover:border-gray-300  border-transparent border-b-2 {{ Request::is("site/*/schedulers*") ? 'text-purple-500 border-purple-300' : '' }}">
                    <a href="{{ route('schedulers.index', ['site' => $site->id]) }}">
                        Schedulers
                    </a>
                </li>
                <li class="text-gray-500  hover:border-gray-300  border-transparent border-b-2 {{ Request::is("site/*/settings") ? 'text-purple-500 border-purple-300' : '' }}">
                    <a href="{{ route('settings.index', ['site' => $site->id]) }}">
                        Settings
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="hidden sm:block">
        <a href="{{ $site->url }}" target="_blank">{{ $site->name }}</a>
    </div>
</div>
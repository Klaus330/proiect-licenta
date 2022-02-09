<div class="w-full p-5 bg-white my-5 rounded">
    <nav>
        <ul class="flex gap-4 justify-start items-center">
            <li class="text-gray-500 hover:border-gray-300 border-transparent border-b-2 {{ Request::is("sites/*") ? 'text-purple-500 border-purple-300' : '' }}">
                <a href="{{ route('sites.index') }}">
                    Uptime
                </a>
            </li>
            <li class="text-gray-500  hover:border-gray-300 border-transparent border-b-2">
                <a href="">
                    Overview
                </a>
            </li>
            <li class="text-gray-500  hover:border-gray-300  border-transparent border-b-2">
                <a href="">
                    Schedulers
                </a>
            </li>
            <li class="text-gray-500  hover:border-gray-300  border-transparent border-b-2">
                <a href="{{ route('settings.index', ['site' => $site->id]) }}">
                    Settings
                </a>
            </li>
        </ul>
    </nav>
</div>
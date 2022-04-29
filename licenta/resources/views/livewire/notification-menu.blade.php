<button 
    x-data="notificationMenu()"
    :class="{'outline-none ring-2 ring-offset-2 ring-indigo-500 text-indigo-500' : showNotificationMenu}"
    class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 relative">
    <span class="sr-only">View notifications</span>
    <svg class="h-6 w-6 @if($unreadNotifications->isNotEmpty()) text-indigo-500 @endif" 
        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"  @click="showNotificationMenu = !showNotificationMenu">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
    </svg>

    <div class="absolute top-full mt-4 right-0 shadow-xl bg-white w-96 flex flex-col justify-start items-start rounded" x-show="showNotificationMenu" @click.away="showNotificationMenu = false;">
        <div class="flex justify-between items-center w-full px-3">
            <h4 class="py-3 text-black font-bold">Unread Notifications</h4>

            @if($unreadNotifications->isNotEmpty())
                <span class="text-xs hover:text-indigo-500" @click="markNotification('all')">
                    Mark all
                </span>
            @endif
        </div>
        <ul class="w-full px-3">
            @forelse ($unreadNotifications->take(4) as $index => $item)
                <li class="text-left py-2 @if($index>=1) border-t border-gray-300 @endif text-gray-800 text-sm flex justify-between items-center w-full">
                    <span class="text-ellipsis">
                        {{$item->data['message']}}
                    </span>
                    <span class="text-xs hover:text-indigo-500 pr-1" @click="markNotification('{{$item->id}}')">
                        <i class="fas fa-times"></i>
                    </span>
                </li>
            @empty
                <li class="text-sm text-gray-500 pb-4"> No unread notifications. </li>   
            @endforelse
            @if($unreadNotifications->isNotEmpty() && $unreadNotifications->count() > 4)
                <li class="text-center py-2">
                    <a href="/profile#notifications" class="text-sm text-indigo-500 hover:text-indigo-600 hover:underline">View all</a>
                </li>
            @endif
        </ul>
    </div>
</button>
@if ($unreadNotifications->isNotEmpty())
    <div class="mt-10">
        <x-jet-action-section>
            <x-slot name="title">
                Notifications
            </x-slot>

            <x-slot name="description">
                See all your notifications
            </x-slot>

            <x-slot name="content">
                <h3 class="text-lg font-medium text-gray-900 flex items-center justify-between w-full">
                    Unread Notifications
                    
                    <span x-data class="text-sm text-indigo-400 hover:text-indigo-600 cursor-pointer" @click="$wire.markNotificationAsRead('all'); Livewire.emit('markNotificationAsRead', 'all')">
                        Mark all as read
                    </span>
                </h3>
                <div class="mt-3 text-sm text-gray-600 w-full">
                    <ul class="w-full"  x-data>
                        @foreach ($unreadNotifications as $index => $item)
                            <li class="w-full flex justify-between items-center @if($index>=1) border-t border-gray-300 @endif py-2" wire:key="{{$item->id}}">
                                <a href="{{$item->data['link']}}" class="text-indigo-500">{{ $item->data['message'] }}</a>

                                <span class="text-xs hover:text-indigo-500 pr-1 cursor-pointer hover:text-indigo-400 text-lg" @click="$wire.markNotificationAsRead('{{$item->id}}'); Livewire.emit('markNotificationAsRead', '{{$item->id}}')">
                                    &times;
                                </span>
                            </li>  
                        @endforeach
                    </ul>
                </div>
            </x-slot>
        </x-jet-action-section>
    </div>
@endif

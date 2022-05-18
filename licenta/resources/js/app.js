require('./bootstrap');
import Alpine from 'alpinejs';
import axios from 'axios';

require('jsoneditor')

window.Alpine = Alpine;

window.notificationMenu = () => {
    return {
        showNotificationMenu: false,
        markNotification: (notification) => {         
            axios.post('/mark-notification-as-read', {
                'notification': notification
            }).then(response => {
                Livewire.emit('markNotificationAsRead', notification);                   
            }).catch(response => {
                alert(response['error'])
            });
        }
    }
}


Alpine.start();
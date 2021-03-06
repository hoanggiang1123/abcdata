import Vue from 'vue';

Vue.filter('datetime', (value) => {
    const date = new Date(value);
    return (date.getDate().toString()).padStart(2, '0') + '/' + ((date.getMonth() + 1).toString()).padStart(2, '0') + '/' + date.getFullYear() + ' ' + (date.getHours().toString()).padStart(2, '0') + ':' + (date.getMinutes().toString()).padStart(2, '0');
})
Vue.filter('dateString', (value) => {
    const date = new Date(value);
    return (date.getDate().toString()).padStart(2, '0') + '/' + ((date.getMonth() + 1).toString()).padStart(2, '0') + '/' + date.getFullYear();
})

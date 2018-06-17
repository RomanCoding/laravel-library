
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import VueRouter from 'vue-router'

window.Vue = require('vue');



Vue.use(VueRouter);


Object.defineProperty(Array.prototype, 'unique', {
    enumerable: false,
    configurable: false,
    writable: false,
    value: function(field=null) {
        let a = this.concat();
        if (field) {
            for (let i=0; i < a.length; ++i) {
                for (let j=i+1; j < a.length; ++j) {
                    if(a[i][field] === a[j][field])
                        a.splice(j--, 1);
                }
            }
        } else {
            for (let i=0; i < a.length; ++i) {
                for (let j=i+1; j < a.length; ++j) {
                    if(a[i] === a[j])
                        a.splice(j--, 1);
                }
            }
        }

        return a;
    }
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

window.bus = new Vue();

Vue.component('files', require('./components/Files.vue'));
Vue.component('item', require('./components/Item.vue'));
Vue.component('users', require('./components/Users.vue'));
Vue.component('folders', require('./components/Folders.vue'));
Vue.component('uploads', require('./components/Uploads.vue'));
Vue.component('file-uploader', require('./components/FileUploader.vue'));
Vue.component('extensions', require('./components/Extensions.vue'));

import Home from './components/Home.vue';
import BusinessPartners from './components/BusinessPartners.vue';
import Files from './components/Files.vue';
import Webinars from './components/Webinars.vue';
import Video from './components/Video.vue';
import Calendar from './components/Calendar.vue';
import Network from './components/Network.vue';

const routes = [
    { path: '', redirect: 'home' },
    { path: '/home', component: Home },
    { path: '/toolkit', component: Files },
    { path: '/partners', component: BusinessPartners },
    { path: '/webinars', component: Webinars },
    { path: '/video', component: Video },
    { path: '/calendar', component: Calendar },
    { path: '/network', component: Network },
];

const router = new VueRouter({
    routes,
    linkActiveClass: 'active',
    mode: 'history'
});

const app = new Vue({
    el: '#app',
    router
});

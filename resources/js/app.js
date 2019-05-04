
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Gate from './gate';
Vue.prototype.$gate = new Gate(window.user);

import DatePicker from 'vue2-datepicker';
Vue.component(DatePicker.name, DatePicker);

import Multiselect from 'vue-multiselect';
Vue.component('multiselect', Multiselect);

import VueFormWizard from 'vue-form-wizard';
Vue.use(VueFormWizard);

import { Form, HasError, AlertError } from 'vform';
window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
Vue.component('pagination', require('laravel-vue-pagination'));

import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: '#01C24F',
    failedColor: '#C10022',
    thickness: '3px',
    transition: {
        speed: '0.2s',
        termination: 300
    }
});

import Swal from 'sweetalert2';
const toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });
window.swal = Swal;  
window.toast = toast;

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

Vue.component(
    'not-found',
    require('./components/NotFound.vue').default
);

Vue.component(
    'data-kompetensi',
    require('./components/frontoffice/Kompetensi/DataKompetensi').default
);

Vue.component(
    'bukti-kompetensi',
    require('./components/frontoffice/Kompetensi/BuktiKompetensiWajib').default
);

import VueRouter from 'vue-router';

Vue.use(VueRouter);

const routes = [
    { path: '/dashboard', component: require('./components/backoffice/Dashboard').default },
    { path: '/skpi', component: require('./components/backoffice/skpi/Skpi').default },
    { path: '/skpi-kompetensi', name:'skpi-kompetensi', props:true, component: require('./components/backoffice/skpi/SkpiKompetensi').default },
    { path: '/mahasiswa-achievement', component: require('./components/backoffice/mahasiswa/MahasiswaAchievement').default },
    { path: '/kompetensi-wajib', component: require('./components/backoffice/kompetensiwajib/KompetensiWajib').default },
    { path: '/mahasiswa', component: require('./components/backoffice/mahasiswa/Mahasiswa').default },
    { path: '/Jurusan', component: require('./components/backoffice/jurusan/Jurusan').default },
    { path: '/bidang-kompetensi', component: require('./components/backoffice/bidangkompetensi/BidangKompetensi').default },
    { path: '/account', component: require('./components/backoffice/account/Account').default },
    { path: '/profile', component: require('./components/Profile').default },
    { path: '/backup', component: require('./components/backoffice/backup/Backup').default },
    { path: '/skpi-submission', component: require('./components/frontoffice/skpi/Skpi').default },
    { path: '/mahasiswa-profile', component: require('./components/frontoffice/mahasiswaprofile/MahasiswaProfile').default },
    { path: '/kompetensi', component: require('./components/frontoffice/kompetensi/Kompetensi').default },
    { path: '*', component: require('./components/NotFound.vue').default }
  ];

  const router = new VueRouter({
      mode: 'history',
      routes
  });

  import moment from 'moment';
  moment.locale('id');
  Vue.filter('date',function(date){
        return moment(date).format('lll');
  });
  Vue.filter('dateSkpi',function(date){
    return moment(date).format('LL');
});


  let cusEvent = new Vue();
  window.cusEvent = cusEvent;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    data:{
        search:''
    },
    methods:{
        searching: _.debounce(() => {
            cusEvent.$emit('Searching');
        },1000)
    }

});

require('./bootstrap');
window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

import Vuetify from 'vuetify';
import 'font-awesome/css/font-awesome.min.css';
import LRU from 'lru-cache';
const themeCache = new LRU({
    max: 1,
    maxAge: 1000,
});

Vue.use(Vuetify, {
    iconfont: 'fa4',
    options: {
        themeCache,
        minifyTheme: function(css) {
            return process.env.NODE_ENV === 'production'
                ? css.replace(/[\s|\r\n|\r|\n]/g, '')
                : css
        },
    },
    theme: {
        base: '#043044',
        base1: '#2CAA6C',
        base2: '#043044',
        base3: '#67B890',
        base4: '#40F69D',
        primary: '#2CAA6C',
        secondary: '#002B40',
        // accent: '#C48F33',
        // error: '#AD3421'
    },
});

import VueI18n from 'vue-i18n';
import pt from 'vuetify/lib/locale/pt';
import en from 'vuetify/lib/locale/en';

Vue.use(VueI18n);
const messages = {
    pt: pt,
    en: en,
};
export const i18n = new VueI18n({
    locale: 'pt',
    messages,
});

import Toasted from 'vue-toasted'
import VueTheMask from 'vue-the-mask'
Vue.use(Toasted);
Vue.use(VueTheMask);

import VueMoment from 'vue-moment';
const moment = require('moment');
require('moment/locale/pt-br');

Vue.use(VueMoment, {moment});
Vue.filter('formatDate', function(value) {
    if (!value) {
        return '';
    }
    return moment(String(value)).format('DD/MM/YYYY');
});

import VuetifyConfirm from './components/plugins/confirm/index';
Vue.use(VuetifyConfirm);

import router from './router';
import store from './store';
import ECharts from 'vue-echarts';
import 'echarts/lib/chart/line';
import 'echarts/lib/chart/bar';
import 'echarts/lib/chart/pie';
import 'echarts/lib/component/polar';
import 'echarts/lib/component/tooltip';
import 'echarts-gl';

Vue.config.productionTip = false;
Vue.component('v-chart', ECharts);

const app = new Vue({
    i18n,
    router,
    store,
    el: '#app',
    data() {
        return {drawer: null}
    }
});

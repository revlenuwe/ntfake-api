import Vue from 'vue'
import axios from 'axios'
import Vuelidate from "vuelidate";
import VueClipboard from 'vue-clipboard2'

Vue.use(Vuelidate)
Vue.use(VueClipboard)

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Vue.component('json-create-form', require('./components/JsonCreateForm').default);

const vm = new Vue({
    el: '#app'
})
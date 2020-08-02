import Vue from 'vue'
import axios from 'axios'
import Vuelidate from "vuelidate";

Vue.use(Vuelidate)

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Vue.component('json-create-form', require('./components/JsonCreateForm').default);

const vm = new Vue({
    el: '#app',
    validations:{}
})
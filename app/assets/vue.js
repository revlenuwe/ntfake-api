import Vue from 'vue'
import axios from 'axios'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Vue.component('json-create-form', require('./components/JsonCreateForm').default);

const vm = new Vue({
    el: '#app'
})
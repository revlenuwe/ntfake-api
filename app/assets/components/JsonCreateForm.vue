<template>
    <form @submit.prevent="createResponse()">
        <div class="form-row">
            <div class="alert alert-danger col col-lg-12" v-if="formError" role="alert">
                Please fill all fields correctly
            </div>
            <div class="col col-lg-6 col-md-12 col-sm-12">
                <div class="form-group">
                    <label for="statusInp">Status Code</label>
                    <select class=" input selectpicker" id="statusInp"  v-model.trim="$v.code.$model">
                        <option value="200">200 OK</option>
                        <option value="201">201 Created</option>
                        <option value="202">202 Accepted</option>
                        <option value="204">204 No Content</option>
                        <option value="301">301 Moved Permanently</option>
                        <option value="302">302 Found</option>
                        <option value="307">307 Temporary Redirect</option>
                        <option value="308">308 Permanent Redirect</option>
                        <option value="400">400 Bad Request</option>
                        <option value="401">401 Unauthorized</option>
                        <option value="403">403 Forbidden</option>
                        <option value="404">404 Not Found</option>
                        <option value="405">405 Method Not Allowed</option>
                        <option value="429">429 Too Many Requests</option>
                        <option value="500">500 Internal Server Error</option>
                        <option value="502">502 Bad Gateway</option>
                        <option value="503">503 Service Unavailable</option>
                        <option value="504">504 Gateway Timeout</option>
                    </select>
                    <div class="error" v-if="!$v.code.required">Field is required</div>
                </div>
            </div>
            <div class="col col-lg-6 col-md-12 col-sm-12">
                <div class="form-group">
                    <label for="methodInp">Method</label>
                    <select class=" input selectpicker" id="methodInp" v-model.trim="$v.method.$model">
                        <option selected value="get">GET</option>
                        <option value="post">POST</option>
                    </select>
                    <div class="error" v-if="!$v.method.required">Field is required</div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="bodyArea">Json Response</label>
            <textarea id="bodyArea"  cols="30" rows="6" class="form-control" placeholder="Json" v-model.trim="$v.body.$model" />
            <div class="error" v-if="!$v.body.isJson">Invalid json</div>
            <div class="error" v-if="formError && !$v.body.required">Field is required</div>
        </div>
        <div class="mb-30" v-if="apiUrl">
            <div class="bg_brand rounded p-2 text-white d-flex justify-content-between">
                <b class="mt-2">{{ apiUrl }}</b>
                <button type="button" v-clipboard:copy="apiUrl" v-clipboard:success="successCopy" class="btn btn-primary p-2">
                    <i class="fas fa-clone" v-if="copied"></i>
                    <i class="far fa-clone" v-else></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</template>

<script>
    import axios from 'axios';

    import { required, minLength } from 'vuelidate/lib/validators'
    const isJson = function (body) {
        try {
            JSON.parse(body)
        }catch (e) {
            return false;
        }
        return true;
    };

    export default {
        name: "JsonCreateForm",
        data () {
            return {
                formError: false,
                code: 200,
                method: 'get',
                body: null,

                copied: false,
                apiUrl: null,
            }
        },
        methods: {
            createResponse () {
                this.$v.$touch()
                if(this.$v.$anyError){
                    this.formError = true
                    this.apiUrl = null
                }else{
                    this.formError = false
                    this.copied = false
                    
                    axios.post('/api/response/create',{
                        code: this.code, method: this.method, body: this.body
                    }).then(response => {
                        this.apiUrl = response.data.api
                        //this.$v.$reset() - not working as expect
                        this.body = null

                    })
                }
            },
            successCopy () {
                this.copied = true
            },
        },
        validations: {
            code: {
                required,
            },
            method: {
                required,
            },
            body: {
                required,
                minLength: minLength(3),
                isJson
            },
        }
    }
</script>

<style scoped>

</style>
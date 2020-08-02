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
                        <option selected value="200">200 OK</option>
                        <option  value="201">201 Created</option>
                        <option  value="202">202 Accepted</option>
                        <option  value="302">302 Found</option>
                    </select>
                    <div class="error" v-if="!$v.code.required">Field is required</div>
                </div>
            </div>
            <div class="col col-lg-6 col-md-12 col-sm-12">
                <div class="form-group">
                    <label for="methodInp">Method</label>
                    <select class=" input selectpicker" id="methodInp" v-model.trim="$v.method.$model">
                        <option selected value="get">GET</option>
                        <option value="post">GET</option>
                    </select>
                    <div class="error" v-if="!$v.method.required">Field is required</div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="bodyArea">Body</label>
            <textarea id="bodyArea"  cols="30" rows="6" class="form-control" placeholder="Body" v-model.trim="$v.body.$model" />
            <div class="error" v-if="!$v.body.isJson">Invalid json</div>
            <div class="error" v-if="formError && !$v.body.required">Field is required</div>
        </div>
        <div class="form-group mb-32">
            <p class="text-gray mb-0">Lorem</p>
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
            }
        },
        methods: {
            createResponse () {
                this.$v.$touch()
                if(this.$v.$anyError){
                    this.formError = true
                }else{

                }
                

            }
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
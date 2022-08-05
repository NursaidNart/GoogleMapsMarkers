<template >
    <div>
        <div class="container">
            <div  class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5> New User </h5>
                    </div>
                    <div class="card-body">
                    <form class="row g-3">
                        <div class="form-group row my-3">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail</label>
                            <div class="col-md-8">
                                <input v-model="user.email" type="text" class="form-control" name="email" placeholder="E-Mail" required>
                            </div>
                        </div>
                        <div class="form-group row my-3">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                            <div class="col-md-8">
                                <input v-model="user.name" type="text" class="form-control" placeholder="Name" required>
                            </div>
                        </div>
                        <div class="form-group row my-3">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                            <div class="col-md-8">
                                <input v-model="user.password" type="text" class="form-control" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="form-group row my-3">
                            <label for="role_type" class="col-md-4 col-form-label text-md-right">Role</label>
                            <div class="col-md-8">
                            <VueMultiselect
                                    v-model="role_type"
                                    :options="role_type_options"
                                    :multiple="false"
                                    :close-on-select="true"
                                    :clear-on-select="true"
                                    :preserve-search="true"
                                    placeholder="Role"
                                    label="value"
                                    track-by="key"
                                    :preselect-first="true"
                                >
                                </VueMultiselect>
                            </div>
                        </div>
                        <div class="form-group row my-3">
                            <label for="permissions" class="col-md-4 col-form-label text-md-right">Permissions</label>
                            <div class="col-md-8">
                               <VueMultiselect
                                    v-model="permissions"
                                    :options="permissions_options"
                                    :multiple="true"
                                    :close-on-select="false"
                                    :clear-on-select="false"
                                    :preserve-search="true"
                                    placeholder="Permissions"
                                    label="value"
                                    track-by="value"
                                    :preselect-first="true"
                                >
                                </VueMultiselect>
                            </div>
                        </div>

                        <div class="col-md-6 offset-md-4 my-2">
                            <button class="btn btn-primary"  @click.stop.prevent="createUser()">Save</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
               <div v-if="show_alert" :class="[{'alert-success':!error} ,{'alert-danger':error} ,'alert alert-dismissible fade show']" role="alert">
                    {{alert_message}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="show_alert = !show_alert"></button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import VueMultiselect from 'vue-multiselect'
export default {
    components: { VueMultiselect },
    data() {
        return {
            user: {},
            selected: null,
            role_type : [],
            role_type_options: [],
            permissions: [],
            permissions_options: [],
            show_alert:false,
            error:false,
            alert_message: null,
        }
    },
    mounted () {
        let app = this;
        for (const [key, value] of Object.entries(app.data.permissions)) {
            app.permissions_options.push({
                'key':key,
                'value':value
            })
        }
        for (const [key, value] of Object.entries(app.data.roles)) {
            app.role_type_options.push({
                'key':key,
                'value':value
            })
        }

    },
    methods: {
        createUser(){
            let app = this;
            let new_permissions = [];
            app.show_alert = false;

            app.permissions.forEach(function (permission){
                new_permissions.push(permission.key);
            })
            if(new_permissions && new_permissions.length > 0){
                app.user.permissions = new_permissions
            }

            if(app.role_type){
                app.user.role_type = app.role_type['key'];
            }

            let model = app.user;
            this.axios.post('/user',model)
                .then(response => {
                    app.show_alert = true;
                    app.error = response.data.error,
                    app.alert_message = response.data.message;
                })
                .catch(error =>{
                    console.log("error",error)
                });

        }
    },
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>

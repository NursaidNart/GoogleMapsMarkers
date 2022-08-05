<template >
    <div>
        <div class="card">
            <div class="card-header">
                Marker Edit
            </div>
            <div class="card-body">
            <form class="row g-3">
                <div class="form-group row my-3">
                    <label for="name" class="col-md-4 col-form-label text-md-right">Title</label>
                    <div class="col-md-8">
                        <input v-model="marker.title" type="text" class="form-control" placeholder="Title" required>
                    </div>
                </div>
                    <div class="form-group row my-3">
                    <label for="lat" class="col-md-4 col-form-label text-md-right">Latitude</label>
                    <div class="col-md-8">
                        <input v-model="marker.lat" type="text" class="form-control" placeholder="Latitude" required>
                    </div>
                </div>
                    <div class="form-group row my-3">
                    <label for="long" class="col-md-4 col-form-label text-md-right">Longitude</label>
                    <div class="col-md-8">
                        <input v-model="marker.long" type="text" class="form-control" placeholder="Longitude" required>
                    </div>
                </div>
                <div class="form-group row my-3">
                    <label for="user" class="col-md-4 col-form-label text-md-right">User</label>
                    <div class="col-md-8">
                    <VueMultiselect
                            v-model="user"
                            :options="user_options"
                            :multiple="false"
                            :close-on-select="true"
                            :clear-on-select="true"
                            :preserve-search="true"
                            placeholder="User"
                            label="value"
                            track-by="key"
                            :preselect-first="true"
                        >
                        </VueMultiselect>
                    </div>
                </div>
                <div class="col-md-6 offset-md-4 my-2">
                    <button class="btn btn-primary" @click.stop.prevent="editMarker()">Save</button>
                </div>
            </form>
            </div>
        </div>
        <div v-if="show_alert" :class="[{'alert-success':!error} ,{'alert-danger':error} ,'alert alert-dismissible fade show mt-3']" role="alert">
            {{alert_message}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="show_alert = !show_alert"></button>
        </div>

    </div>
</template>
<script>
import VueMultiselect from 'vue-multiselect'
export default {
    components: { VueMultiselect },
    props: {
        marker: Object,
    },
    data() {
        return {
            user: {},
            user_options: [],
            show_alert:false,
            error:false,
            alert_message: null,
        }
    },
    mounted () {
        let app = this;


        app.data.users.forEach(function(user){
            app.user_options.push({
                'key':user.id,
                'value':user.name
            });
        })

        if(app.marker.user_id){
            app.user = {
                'key': app.marker.user.id,
                'value': app.marker.user.name
            }
        }else{
            app.user = {};
        }
    },
    methods: {
        editMarker(){
            let app = this;
            let new_permissions = [];
            app.show_alert = false;

            if(app.user && app.user['key']){
                app.marker.user_id = app.user['key'];
            }

            let model = app.marker;
            this.axios.post('/marker',model)
                .then(response => {
                    app.show_alert = true;
                    app.error = response.data.error,
                    app.alert_message = response.data.message;
                    app.$emit('updateMarkerList');
                })
                .catch(error =>{
                    console.log("error",error)
                });

        }
    },
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<template >
    <div>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Google Map
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Google Map</h5>
                            <div id="map">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div v-if="show_marker_edit" class="mb-3" >
                        <marker-edit
                            :marker="selected_marker"
                            @updateMarkerList="updateMarkerList"
                            :key="component_key"
                        ></marker-edit>
                    </div>
                    <div>
                        <div class="card">
                            <div class="card-header">
                                Markers
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Markers</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">User Name</th>
                                            <th scope="col">Latitude</th>
                                            <th scope="col">Longitude</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="marker in markers">
                                            <th scope="row">{{marker.id}}</th>
                                            <td>{{marker.title}}</td>
                                            <td>{{marker.user?marker.user.name:''}}</td>
                                            <td>{{marker.lat}}</td>
                                            <td>{{marker.long}}</td>
                                            <td>
                                                <div class="d-grid  gap-2 d-md-flex justify-content-md-left">
                                                    <button type="button" class="btn btn-primary btn-sm" @click="editMarker(marker)">Edit</button>
                                                    <button type="button" class="btn btn-danger btn-sm" v-if="marker.id"  @click="deleteMarker(marker)">Delete</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                         <div v-if="show_alert" :class="[{'alert-success':!error} ,{'alert-danger':error} ,'alert alert-dismissible fade show']" role="alert">
                            {{alert_message}}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="show_alert = !show_alert"></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
<script>
import MarkerEdit from "@/components/MarkerEdit.vue";
export default {
    components: { MarkerEdit },
    data() {
        return {
            component_key: 0,
            selected_marker : null,
            markers: [],
            show_marker_edit: false,
            show_alert:false,
            error:false,
            alert_message: null,
        }
    },
    mounted () {
        let app = this;
        // app.initMap();// TODO google map oluştur
        app.getMarkers();

    },
    methods: {
        getMarkers(){
            let app = this;
            this.axios.get('marker')
                .then(response => {
                    app.markers = response.data.markers;

                    app.googleAddMarker();
                })
                .catch(error =>{
                    console.log("error",error)
                });

        },
        googleAddMarker(){
            let app = this;
            app.markers.push({
                'title':'google added',
                'lat' : 2222,
                'long' : 3333,
            });
            // app.googleMapClickedMarker();// TODO bu değişecek
        },
        googleMapClickedMarker(){
            let app = this;
            let index = 0;
            app.selected_marker = app.markers[index];
            app.show_marker_edit = true;
            app.component_key += 1;

        },
        editMarker(marker){
            let app = this;
            app.show_marker_edit = false;
            app.component_key += 1;
            app.selected_marker = marker;
            app.show_marker_edit = true;
        },
        deleteMarker(marker){
            let app = this;
            app.show_alert = false;
            this.axios.delete('marker/' + marker.id)
                .then(response => {
                    app.show_alert = true;
                    app.error = response.data.error,
                    app.alert_message = response.data.message;
                    app.getMarkers();
                })
                .catch(error =>{
                    console.log("error",error)
                });
        },
        updateMarkerList(){
            let app = this;
            app.getMarkers();
        }
        // initMap() {
        //   const myLatLng = { lat: 22.2734719, lng: 70.7512559 };
        //   const map = new google.maps.Map(document.getElementById("map"), {
        //     zoom: 5,
        //     center: myLatLng,
        //   });

        //   new google.maps.Marker({
        //     position: myLatLng,
        //     map,
        //   });
        // }


    },
}
</script>


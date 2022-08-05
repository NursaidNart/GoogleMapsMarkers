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
                                        <th scope="col">Latitude</th>
                                        <th scope="col">Longitude</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="marker in markers">
                                        <th scope="row">{{marker.id}}</th>
                                        <td>{{marker.title}}</td>
                                        <td>{{marker.lat}}</td>
                                        <td>{{marker.long}}</td>
                                        <td>
                                            <div class="d-grid  gap-2 d-md-flex justify-content-md-left">
                                                <button type="button" class="btn btn-primary btn-sm" @click="showOnMapMarker(marker)">Show</button>
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
</template>
<script>
export default {
    data() {
        return {
            markers: [],
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
            this.axios.get('get_my_markers')
                .then(response => {
                    app.markers = response.data.markers;

                })
                .catch(error =>{
                    console.log("error",error)
                });

        },
        showOnMapMarker(marker){
            //TODO
        },
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

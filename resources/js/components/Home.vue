<template>
    <div>
        <div class="container">
            <div class="card">
                <div class="card-header">
                    Pages
                </div>
                <div class="card-body">
                <div class="row">
                    <div class="col-sm-3" v-for="(link, index) in links" :key="index" >
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{link.title}}</h5>
                                <router-link  :to="link.key"  aria-current="page">
                                    <a class="btn btn-primary">Go</a>
                                </router-link>

                            </div>
                        </div>
                    </div>
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
            links: [],
        }
    },
    mounted () {
        let app = this;
        app.links = app.data.links.filter(links => links.title != 'Home');


    },
    methods: {
    },
}
</script>

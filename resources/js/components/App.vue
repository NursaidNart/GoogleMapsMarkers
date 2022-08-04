<template>
    <NavBar></NavBar>
    <div class="container">
        <router-view></router-view>
    </div>
</template>

<script>
import NavBar from './NavBar.vue'

export default {
    components: {
        NavBar: NavBar
    },
    mounted() {
        this.$router.push({ path: "welcome"})
        //TODO get link and redirect this.$route.fullPath
    }
}
</script>

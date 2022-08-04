<template>
<div class="container">
    <header class="d-flex justify-content-center py-3">
        <ul class="nav nav-pills">
            <li class="nav-item " v-for="(link, index) in links" :key="index" >
                <router-link  :to="link.key"  aria-current="page" v-slot="{isActive}">
                    <NavLink :class="['nav-link', {active:isActive}]">{{link.title}}</NavLink>
               </router-link>
            </li>
        </ul>
    </header>
</div>
</template>
<script>
export default {
    data() {
        return {
            links: this.data.links,
        }
    },
    mounted() {


    },
    methods: {

    },
}
</script>


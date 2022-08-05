

import UserCreate from './components/UserCreate.vue';
import UserEdit from './components/UserEdit.vue';
import UserList from './components/UserList.vue';
import MyMarkersShow from './components/MyMarkersShow.vue';
import GoogleMapsMarkersCreate from '@/components/GoogleMapsMarkersCreate.vue';
import Home from '@/components/Home.vue';
export const routes = [];
data.links = [];
routes.push({
    path: '/welcome',
    name: 'home',
    component: Home
});
data.links.push(
    {
        title: 'Home',
        key: '/welcome',
    });

if(data && data.routes && data.routes){
    data.routes.forEach((route) =>{

        if(route.key == 'create_user'){
            data.links.push(
                {
                    title: 'Users',
                    key: '/user_list',
                });
            routes.push({
                path: '/user_list',
                name: 'list',
                component: UserList
            });
            routes.push({
                path: '/create_user',
                name:route.title,
                component: UserCreate
            });
            routes.push({
                path: '/edit_user/:id',
                name: 'Edit User',
                component: UserEdit
            });
        }
        if(route.key == 'create_google_maps_markers'){
            data.links.push(
                {
                    title: 'GoogleMapsMarkersCreate',
                    key: '/create_google_maps_markers',
                });
            routes.push({
                path: '/create_google_maps_markers',
                name:route.title,
                component: GoogleMapsMarkersCreate
            });
        }
        if(route.key == 'show_my_markers'){
            data.links.push(
                {
                    title: 'MyMarkersShow',
                    key: '/show_my_markers',
                });
            routes.push({
                path: '/show_my_markers',
                name:route.title,
                component: MyMarkersShow
            });
        }
    })
}


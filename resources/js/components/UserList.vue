<template>
    <div>
        <div class="container">
            <div class="card">
                <div class="card-header">
                    Users
                <button type="button" class="btn btn-success btn-sm float-end" @click="createUser()">New User</button>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Users </h5>
                    <!-- TODO add filter -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">User Role</th>
                                <th scope="col">Email</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users">
                                <th scope="row">{{user.id}}</th>
                                <td>{{user.name}}</td>
                                <td>{{user.role_type}}</td>
                                <td>{{user.email}}</td>
                                <td>
                                    <div class="d-grid  gap-2 d-md-flex justify-content-md-left">
                                        <button type="button" class="btn btn-primary btn-sm" @click="editUser(user)">Edit</button>
                                        <button type="button" class="btn btn-danger btn-sm"  @click="deleteUser(user)">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- TODO add paginate -->
                </div>
            </div>

            <div v-if="show_alert" :class="[{'alert-success':!error} ,{'alert-danger':error} ,'alert alert-dismissible fade show']" role="alert">
                {{alert_message}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="show_alert = !show_alert"></button>
            </div>

        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            users: [],
            show_alert:false,
            error:false,
            alert_message: null,
        }
    },
    mounted () {
        let app = this;
        app.getUsers();

    },
    methods: {
        getUsers(){
            let app = this;
            this.axios.get('user')
                .then(response => {
                    app.users = response.data.users;
                })
                .catch(error =>{
                    console.log("error",error)
                });
        },
        createUser(){
            this.$router.push({ path: "create_user"})
        },
        editUser(user){
            this.$router.push({ path: "edit_user/"+user.id})
        },
        deleteUser(user){
            let app = this;
            app.show_alert = false;
            this.axios.delete('user/' + user.id)
                .then(response => {
                    app.show_alert = true;
                    app.error = response.data.error,
                    app.alert_message = response.data.message;
                    app.getUsers();
                })
                .catch(error =>{
                    console.log("error",error)
                });

        }

    },
}
</script>

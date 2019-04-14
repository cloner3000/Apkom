<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
               <div class="container-fluid">
                   <form>
                    <div class="row">
                        <div class="col-md-3">
                            <!-- Profile Image -->
                            <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle" :src="getUpdatePhoto()" alt="User photo">
                                </div>
                                <h3 class="profile-username text-center">{{ user.name }}</h3>
                                <p class="text-muted text-center">{{ user.role }}</p>
                                <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Created</b> <br><a>{{ user.created_at | date }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Last Updated</b> <br><a>{{ user.updated_at | date }}</a>
                                </li>
                                </ul>

                                <button @click.prevent="updateProfile" type="submit" class="btn btn-success btn-block"><b>Update</b></button>
                            </div>
                            <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Profile</h3>
                                </div>
                                <div class="card-body">
                                        <div class="form-group">
                                            <label for="inputName">Name</label>
                                            <input v-model="form.name" type="text" name="name"
                                                class="form-control" placeholder="Full Name" :class="{ 'is-invalid': form.errors.has('name') }" id="inputName">
                                            <has-error :form="form" field="name"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail">Email</label>
                                            <input v-model="form.email" type="text" name="email"
                                                class="form-control" placeholder="Email Address" :class="{ 'is-invalid': form.errors.has('email') }" id="inputEmail">
                                            <has-error :form="form" field="email"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword">Password</label>
                                            <input v-model="form.password" type="password" name="password"
                                                class="form-control" placeholder="Password" :class="{ 'is-invalid': form.errors.has('password') }" id="inputPassword">
                                            <has-error :form="form" field="password"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPhoto">Photo</label>
                                            <input class="form-control-file" @change="updatePhoto" type="file" name="photo" id="inputPhoto" placeholder="Photo" :class="{ 'is-invalid': form.errors.has('photo') }" accept="image/*" >
                                            <has-error :form="form" field="photo"></has-error>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
              user:{},
              form: new Form({
              id:'',
              name:'',
              email:'',
              password:'',
              role:'',
              photo:''
            })
            }
        },
        methods: {
            getUser(){
                axios.get('api/profile')
                .then(response => {
                    this.user = response.data;
                    this.form.fill(this.user);
                });
            },
            updateProfile(e){
                this.$Progress.start();
                this.form.put('api/profile/')
                .then(() => {
                    cusEvent.$emit('ReloadData');
                    toast.fire({
                        type: 'success',
                        title: 'User Updated successfully'
                    });
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
            getUpdatePhoto(){
                return this.form.photo.indexOf('base64') != -1 ? this.form.photo : 'img/profile/' + this.form.photo; 
            },
            updatePhoto(e){
                let file = e.target.files[0];
                if(file['size'] < 2097152){   
                    let reader = new FileReader();
                    reader.onloadend = (file) => {
                        this.form.photo = reader.result;
                    }
                    reader.readAsDataURL(file); 
                }else{
                    e.target.value = ''
                    swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'You are uploading a large file! maximum is 2 MB'
                  });
                }
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            this.getUser();
            cusEvent.$on('ReloadData', () => {
                this.getUser();
                
            });
        }
    }
</script>

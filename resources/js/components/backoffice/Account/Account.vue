<template>
    <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">User Table</h3>
                <div class="card-tools">
                  <button class="btn btn-success" @click="newModal"><i class="fas fa-user-plus"></i> Add New</button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th>Action</th>
                  </tr>
                  <tr v-for="user in users.data" :key="user.id">
                    <td>{{user.id}}</td>
                    <td>{{user.name}}</td>
                    <td>{{user.email}}</td>
                    <td>{{user.role}}</td>
                    <td>{{user.created_at | date }}</td>
                    <td>
                        <a href="#" @click="editModal(user)"><i  class="fas fa-edit"></i></a>
                        <a href="#" @click="deleteUser(user.id)"><i  class="fas fa-trash text-red"></i></a>
                    </td>
                  </tr>

                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="users" @pagination-change-page="getUsers"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <div id="modalForm" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!editMode" class="modal-title">Add New Account</h5>
                        <h5 v-show="editMode" class="modal-title">Edit Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateUser() : createUser()">
                    <div class="modal-body">
                        <div class="form-group">
                          <label>Name</label>
                          <input v-model="form.name" type="text" name="name"
                            class="form-control" placeholder="Full Name" :class="{ 'is-invalid': form.errors.has('name') }">
                          <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group">
                          <label>Email</label>
                          <input v-model="form.email" type="text" name="email"
                            class="form-control" placeholder="Email Address" :class="{ 'is-invalid': form.errors.has('email') }">
                          <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="form-group">
                          <label>Password</label>
                          <input v-model="form.password" type="password" name="password"
                            class="form-control" placeholder="Password" :class="{ 'is-invalid': form.errors.has('password') }">
                          <has-error :form="form" field="password"></has-error>
                        </div>
                        <div class="form-group">
                          <label>Role</label>
                          <select name="role" v-model="form.role" id="type" class="form-control" :class="{
                            'is-invalid': form.errors.has('role')}">
                            <option value="" selected>Choose Role</option>
                            <option value="Kaprodi">Kepala Program Studi</option>
                            <option value="Bagian Akademik">Bagian Akademik</option>
                            <option value="Wakil Rektor">Wakil Rektor</option>
                            <option value="Mahasiswa">Mahasiswa</option>
                          </select>
                          <has-error :form="form" field="role"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button v-show="!editMode" type="submit" class="btn btn-primary">Add</button>
                      <button v-show="editMode" type="submit" class="btn btn-success">Save</button>
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
          return{
            users : {},
            form : new Form({
              id:'',
              name:'',
              email:'',
              password:'',
              role:''
            }),
            editMode:false 
          }
        },
        methods:{
          newModal(){
            this.editMode = false;
            this.form.reset ();
            this.form.clear ();
            $('#modalForm').modal('show');
          },
          editModal(user){
            this.editMode = true;
            this.form.reset ();
            this.form.clear ();
            $('#modalForm').modal('show');
            this.form.fill(user);
          },
          getUsers(page = 1) {
            axios.get('api/user?page=' + page)
              .then(response => {
                this.users = response.data;
              });
          },
          createUser(){
            this.$Progress.start();
            this.form.post('api/user')
            .then(() => {
              cusEvent.$emit('ReloadData');
              $('#modalForm').modal('hide');
              toast.fire({
                type: 'success',
                title: 'User Created successfully'
              });
              this.$Progress.finish();
            }).catch(() => {
              this.$Progress.fail();
            });
          },
          updateUser(){
            this.$Progress.start();
            this.form.put('api/user/'+this.form.id)
            .then(() => {
              cusEvent.$emit('ReloadData');
              $('#modalForm').modal('hide');
              toast.fire({
                type: 'success',
                title: 'User Updated successfully'
              });
              this.$Progress.finish();
            }).catch(() => {
              this.$Progress.fail();
            });
          },
          deleteUser(id){
            swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.value){ 
                this.form.delete('api/user/'+id)
                .then(() => {
                  this.$Progress.start();
                  swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                  );
                  cusEvent.$emit('ReloadData');
                  this.$Progress.finish();
                })
                .catch(() => {
                  this.$Progress.fail();
                  swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!'
                  })
                });
              }
            });
          }
        },
        created() {
          cusEvent.$on('Searching',() => {
              let query = this.$parent.search;
              axios.get('api/findUser?q=' + query)
              .then((response) => {
                  this.users = response.data
              })
              .catch(() => {
                
              })
          });
          this.getUsers();
          cusEvent.$on('ReloadData', () => {
            this.getUsers();
          });
          cusEvent.$on('Searching', () => {
            
          });
        }
    }
</script>

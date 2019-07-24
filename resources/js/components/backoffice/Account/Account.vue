<template>
    <div class="container">
      <div v-if="this.$gate.isWarek()">
        <div class="row mt-4">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Pengelolaan Akun</h3>
                <div class="card-tools">
                  <button class="btn btn-primary" @click="newModal"><i class="fas fa-user-plus"></i> Tambah Data</button>
                  <button @click="exportUsers('Accounts.pdf')" class="btn btn-danger"><i class="fas fa-file-pdf"></i></button>
                  <button @click="exportUsers('Accounts.xlsx')" class="btn btn-success"><i class="fas fa-file-excel"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Hak Akses</th>
                    <th>Tanggal Dibuat</th>
                    <th width="12%" class="text-center">Aksi</th>
                  </tr>
                  <tr v-for="(data, index) in users.data" :key="index">
                    <td>{{users.meta.from+index}}</td>
                    <td>{{data.name}}</td>
                    <td>{{data.email}}</td>
                    <td>{{data.role}}</td>
                    <td>{{data.created_at | date }}</td>
                    <td class="text-center">
                        <button @click="editModal(data)" class="btn btn-link"><i  class="fas fa-edit"></i></button>
                        <button @click="deleteUser(data.id)" class="btn btn-link"><i  class="fas fa-trash text-red"></i></button>
                    </td>
                  </tr>

                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination v-if="!searching" :data="users" align="center" @pagination-change-page="getUsers"></pagination>
                  <pagination v-else :data="users" align="center" @pagination-change-page="searchUser"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <div id="modalForm" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!editMode" class="modal-title">Tambah Akun Baru</h5>
                        <h5 v-show="editMode" class="modal-title">Ubah Akun</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateUser() : createUser()">
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="inputName">Nama</label>
                          <input v-model="form.name" type="text" name="name"
                            class="form-control" placeholder="Nama Lengkap" :class="{ 'is-invalid': form.errors.has('name') }" id="inputName">
                          <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail">Email</label>
                          <input v-model="form.email" type="text" name="email"
                            class="form-control" placeholder="Alamat Email" :class="{ 'is-invalid': form.errors.has('email') }" id="inputEmail">
                          <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="form-group">
                          <label for="inputPassword">Kata Sandi</label>
                          <input v-model="form.password" type="password" name="password"
                            class="form-control" placeholder="Kata Sandi" :class="{ 'is-invalid': form.errors.has('password') }" id="inputPassword">
                          <has-error :form="form" field="password"></has-error>
                        </div>
                        <div class="form-group">
                          <label for="inputRole">Hak Akses</label>
                          <select name="role" v-model="form.role" id="inputRole" class="form-control" :class="{
                            'is-invalid': form.errors.has('role')}">
                            <option value="" selected>Pilih Hak Akses</option>
                            <option value="Kaprodi">Kepala Program Studi</option>
                            <option value="Bagian Akademik">Bagian Akademik</option>
                            <option value="Wakil Rektor">Wakil Rektor</option>
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
      <div v-else class="row">
        <not-found></not-found>
      </div>
    </div>
</template>

<script>
    export default {
        data(){
          return{
            users : {},
            searching: false,
            editMode:false,
            form : new Form({
              id:'',
              name:'',
              email:'',
              password:'',
              role:''
            })
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
            if(this.$gate.isWarek()){
              this.$Progress.start();
              axios.get('api/user?page=' + page)
              .then(response => {
                this.users = response.data;
                this.$Progress.finish();
              })
              .catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Gagal memuat data akun'
              });
              });
            }
          },
          createUser(){
            this.$Progress.start();
            this.form.post('api/user')
            .then(() => {
              cusEvent.$emit('ReloadData');
              $('#modalForm').modal('hide');
              toast.fire({
                type: 'success',
                title: 'Berhasil menambah akun'
              });
              this.$Progress.finish();
            })
            .catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Gagal menambah akun'
              });
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
                title: 'Berhasil mengubah akun'
              });
              this.$Progress.finish();
            }).catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Gagal mengubah akun'
              });
            });
          },
          deleteUser(id){
            swal.fire({
              title: 'Anda yakin?',
              text: "Anda tidak akan dapat mengembalikan ini!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
              if (result.value){
                this.form.delete('api/user/'+id)
                .then(() => {
                  this.$Progress.start();
                  swal.fire(
                    'Deleted!',
                    'Berhasil menghapus akun.',
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
                    text: 'Gagal menghapus akun'
                  });
                });
              }
            });
          },
          exportUsers(fileType){
            this.$Progress.start();
            axios({
              url: 'api/user/export',
              method: 'GET',
              params: {type: fileType},
              responseType: 'blob',
            }).then((response) => {
              const url = window.URL.createObjectURL(new Blob([response.data]));
              const link = document.createElement('a');
              link.href = url;
              link.setAttribute('download', fileType);
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);
              this.$Progress.finish();
            })
            .catch(() => {
              this.$Progress.fail();
              toast.fire({
              type: 'error',
              title: 'Gagal ekspor akun'
              });
            })
          },
          searchUser(page = 1){
            let query = this.$parent.search;
            if(this.$parent.search != ''){
              this.$Progress.start();
              this.searching= true;
              axios.get('api/user/find?q=' + query + '&page='+ page)
              .then((response) => {
                  this.users = response.data
                  this.$Progress.finish();
              })
              .catch(() => {
                  this.$Progress.fail();
              });
            }else{
              this.searching= false;
              cusEvent.$emit('ReloadData');
            } 
          }
        },
        created() {
          this.$parent.search = '';
          this.getUsers();
          cusEvent.$on('Searching', this.searchUser);
          cusEvent.$on('ReloadData', this.getUsers);
        },
        beforeDestroy(){
          cusEvent.$off('Searching', this.searchUser);
          cusEvent.$off('ReloadData', this.getUsers);
        }
    }
</script>

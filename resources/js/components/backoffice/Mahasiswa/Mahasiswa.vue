<template>
    <div class="container">
      <div v-if="this.$gate.isWarek()">
        <div class="row mt-4">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Manage Mahasiswa</h3>
                <div class="card-tools">
                  <button class="btn btn-primary" @click="newModal"><i class="fas fa-plus-square"></i> Add New</button>
                  <button @click="exportMahasiswa('Mahasiswa.pdf')" class="btn btn-danger"><i class="fas fa-file-pdf"></i></button>
                  <button @click="exportMahasiswa('Mahasiswa.xlsx')" class="btn btn-success"><i class="fas fa-file-excel"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Npm</th>
                    <th>Jurusan</th>
                    <th>No Ijazah</th>
                    <th>Ipk</th>
                    <th width="10%" class="text-center">Total Point</th>
                    <th width="15%" class="text-center">Action</th>
                  </tr>
                  <tr v-for="(data, index) in mahasiswa.data" :key="index">
                    <td>{{mahasiswa.meta.from+index}}</td>
                    <td>{{data.nama}}</td>
                    <td>{{data.npm}}</td>
                    <td>{{data.jurusan.nama_jurusan}}</td>
                    <td>{{data.no_ijazah}}</td>
                    <td>{{data.ipk | ipkFormat}}</td>
                    <td class="text-center">{{data.total_point}}</td>
                    <td class="text-center">
                        <button @click="viewModal(data)" class="btn btn-link"><i  class="fas fa-eye"></i></button>
                        <button @click="deleteMahasiswa(data.id)" class="btn btn-link"><i  class="fas fa-trash text-red"></i></button>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination v-if="!searching"  :data="mahasiswa" align="center" @pagination-change-page="getMahasiswa"></pagination>
                  <pagination v-else  :data="mahasiswa" align="center" @pagination-change-page="searchMahasiswa"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <div id="modalForm" class="modal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!editMode" class="modal-title">Add New Mahasiswa</h5>
                        <h5 v-show="editMode" class="modal-title">Edit Mahasiswa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateMahasiswa() : createMahasiswa()">
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="inputNama">Nama Lengkap</label>
                          <input v-model="form.nama" type="text" name="nama" class="form-control" placeholder="Nama Lengkap" :class="{ 'is-invalid': form.errors.has('nama') }" id="inputNama">
                          <has-error :form="form" field="nama"></has-error>
                        </div>
                        <div class="form-group">
                          <label for="inputNama">Email</label>
                          <input v-model="form.user.email" type="text" name="email" class="form-control" placeholder="Email" :class="{ 'is-invalid': form.errors.has('email') }" id="inputEmail">
                          <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="row">
                          <div class="col-md-6">    
                            <div class="form-group">
                                <label for="inputKotaLahir">Kota Kelahiran</label><br>
                                <input v-model="form.kota_lahir" type="text" name="kota_lahir"
                                class="form-control" placeholder="Kota Kelahiran" :class="{ 'is-invalid': form.errors.has('kota_lahir') }" id="inputKotaLahir">
                                <has-error :form="form" field="kota_lahir"></has-error>
                            </div>
                          </div>
                          <div class="col-md-6">    
                             <div class="form-group">
                                <label for="inputTglLahir">Tanggal Kelahiran</label><br>
                                <date-picker v-model="form.tgl_lahir" lang="en" value-type="format" name="tgl_lahir" id="inputTglLahir" class="form-control-file" 
                                :class="{ 'is-invalid': form.errors.has('tgl_lahir') }"></date-picker>
                                <has-error :form="form" field="tgl_lahir"></has-error>
                            </div>  
                          </div>
                        </div>
                        <div class="form-group">
                            <label for="inputNoIjazah">No Ijazah</label>
                            <input v-model="form.no_ijazah" type="text" name="no_ijazah"
                                class="form-control" placeholder="No Ijazah" :class="{ 'is-invalid': form.errors.has('no_ijazah') }" id="inputNoIjazah">
                            <has-error :form="form" field="no_ijazah"></has-error>
                        </div>
                         <div class="row">
                            <div class="col-md-6">        
                                <div class="form-group">
                                    <label for="inputNpm">Nomor Pokok Mahasiswa</label>
                                    <input v-model="form.npm"  v-on:input="changeName()" type="text" name="npm"
                                    class="form-control" placeholder="Nomor Pokok Mahasiswa" :class="{ 'is-invalid': form.errors.has('npm') }" id="inputNpm">
                                    <has-error :form="form" field="npm"></has-error>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputIpk">Indeks Prestasi Kumulatif</label>
                                    <input v-model="form.ipk" type="text" name="ipk"
                                    class="form-control" placeholder="Indeks Prestasi Kumulatif" :class="{ 'is-invalid': form.errors.has('ipk') }" id="inputIpk">
                                    <has-error :form="form" field="ipk"></has-error>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputJurusan">Jurusan</label>
                            <select name="id_jurusan" v-model="form.id_jurusan" id="inputJurusan" class="form-control" :class="{
                                'is-invalid': form.errors.has('id_jurusan')}">
                                <option value="" selected>Choose Jurusan</option>
                                <option v-for="data in jurusan.data" :key="data.id" v-bind:value="data.id">{{ data.nama_jurusan }}</option>
                            </select>
                            <has-error :form="form" field="id_jurusan"></has-error>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputTglMasuk">Tanggal Mulai Studi</label><br>
                                    <date-picker v-model="form.tgl_masuk" lang="en"  name="tgl_masuk" value-type="format" id="inputTglMasuk" class="form-control-file" 
                                    :class="{ 'is-invalid': form.errors.has('tgl_masuk') }"></date-picker>
                                    <has-error :form="form" field="tgl_masuk"></has-error>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputTglLulus">Tanggal Kelulusan</label><br>
                                    <date-picker v-model="form.tgl_lulus" lang="en"  name="tgl_lulus" value-type="format" id="inputTglLulus" class="form-control-file" 
                                    :class="{ 'is-invalid': form.errors.has('tgl_lulus') }"></date-picker>
                                    <has-error :form="form" field="tgl_lulus"></has-error>
                                </div>
                            </div>
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
        <div id="modalDetail" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title">
                            <i class="fas fa-user-graduate mr-1"></i>
                             Mahasiswa Detail
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <strong><i class="fas fa-user-alt mr-1"></i>Nama</strong>
                            <p class="text-muted">
                                {{form.nama}}
                            </p>
                            <hr>
                            <strong><i class="fas fa-birthday-cake mr-1"></i>Tempat Tanggal Lahir</strong>
                            <p class="text-muted">
                                {{form.kota_lahir}}, {{form.tgl_lahir}}
                            </p>
                            <hr>
                            <strong><i class="fas fa-credit-card mr-1"></i>Nomor Pokok Mahasiswa</strong>
                            <p class="text-muted">
                                {{form.npm}}
                            </p>
                            <hr>
                            <strong><i class="fas fa-graduation-cap mr-1"></i>Jurusan</strong>
                            <p class="text-muted">
                                {{form.jurusan.nama_jurusan}} - {{form.jurusan.fakultas}}
                            </p>
                            <hr>
                            <strong><i class="fas fa-id-card mr-1"></i>Nomor Ijazah</strong>
                            <p class="text-muted">
                                {{form.no_ijazah}}
                            </p>
                            <hr>
                            <strong><i class="fas fa-calendar-alt mr-1"></i>Periode Studi</strong>
                            <p class="text-muted">
                                {{form.tgl_masuk}} - {{form.tgl_lulus}}
                            </p>
                            <hr>
                            <strong><i class="fas fa-trophy mr-1"></i>Indeks Prestasi Kumulatif</strong>
                            <p class="text-muted">
                                {{form.ipk}}
                            </p>
                            <hr>
                            <strong><i class="fas fa-medal mr-1"></i>Total Point</strong>
                            <p class="text-muted">
                                {{form.total_point}}
                            </p>
                            <hr>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>     
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
            mahasiswa : {},
            jurusan : {},
            nama:'',
            searching: false,
            editMode:false,
            form: new Form({
              id:'',
              id_account:'',
              id_jurusan:'',
              npm:'',
              nama:'',
              kota_lahir:'',
              tgl_lahir:'',
              no_ijazah:'',
              tgl_masuk:'',
              tgl_lulus:'',
              ipk:'',
              total_point:'',
              jurusan:{
                  nama_jurusan:'',
                  fakultas:''
              },
              user:{
                name:'',
                email:'',
                password:''
              }
            }),
            account: new Form({
              name:'',
              role: 'Mahasiswa',
              email:'',
              password:''
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
          editModal(mahasiswa){
            this.editMode = true;
            this.form.reset ();
            this.form.clear ();
            $('#modalForm').modal('show');
            this.form.fill(mahasiswa);
          },
          viewModal(mahasiswa){
            $('#modalDetail').modal('show');
            this.form.fill(mahasiswa)
          },
          getJurusan(){
              if(this.$gate.isWarek()){
                  this.$Progress.start();
                  axios.get('api/jurusan/select')
                  .then(response => {
                      this.jurusan = response.data;
                      this.$Progress.finish();
                  })
                  .catch(() => {
                      this.$Progress.fail();
                      toast.fire({
                          type: 'error',
                          title: 'Load data jurusan failed'
                      });
                  });
              }
          },
          getMahasiswa(page = 1) {
            if(this.$gate.isWarek()){
              this.$Progress.start();
              axios.get('api/mahasiswa?page=' + page)
              .then(response => {
                this.mahasiswa = response.data;
                this.$Progress.finish();
              })
              .catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Load data mahasiswa failed'
              });
              });
            }
          },
          createMahasiswa(){
            this.$Progress.start();
            this.account.post('api/user/add')
            .then((response) => {
              this.form.id_account = response.data;
              this.form.post('api/mahasiswa')
              .then(() => {
                cusEvent.$emit('ReloadData');
                $('#modalForm').modal('hide');
                toast.fire({
                  type: 'success',
                  title: 'Mahasiswa created successfully'
                });
                this.$Progress.finish();
              })
              .catch(() => {
                this.$Progress.fail();
                toast.fire({
                  type: 'error',
                  title: 'Mahasiswa create failed'
                });
              });
            }).catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Mahasiswa create failed'
              });
            })
          },
          updateMahasiswa(){
            this.$Progress.start();
            this.form.put('api/mahasiswa/'+this.form.id)
            .then(() => {
              cusEvent.$emit('ReloadData');
              $('#modalForm').modal('hide');
              toast.fire({
                type: 'success',
                title: 'Mahasiswa updated successfully'
              });
              this.$Progress.finish();
            }).catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Mahasiswa update failed'
              });
            });
          },
          deleteMahasiswa(id){
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
                this.form.delete('api/mahasiswa/'+id)
                .then(() => {
                  this.$Progress.start();
                  swal.fire(
                    'Deleted!',
                    'Mahasiswa has been deleted.',
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
                    text: 'Mahasiswa delete failed'
                  });
                });
              }
            });
          },
          exportMahasiswa(fileType){
            this.$Progress.start();
            axios({
              url: 'api/mahasiswa/export',
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
              title: 'Mahasiswa export failed'
              });
            })
          },
          searchMahasiswa(page = 1){
            let query = this.$parent.search;
            if(this.$parent.search != ''){
              this.$Progress.start();
              this.searching= true;
              axios.get('api/mahasiswa/find?q=' + query + '&page='+ page)
              .then((response) => {
                  this.mahasiswa = response.data
                  this.$Progress.finish();
              })
              .catch(() => {
                  this.$Progress.fail();
              });
            }else{
              this.searching= false;
              cusEvent.$emit('ReloadData');
            }
          },
          changeName(){
            this.account.name = this.form.nama;
            this.account.email = this.form.user.email;
            this.account.password = this.form.npm;
          },
        },
        created() {
          this.$parent.search = '';
          this.getMahasiswa();
          this.getJurusan();
          cusEvent.$on('Searching', this.searchMahasiswa);
          cusEvent.$on('ReloadData', this.getMahasiswa);
        },
        beforeDestroy(){
          cusEvent.$off('Searching', this.searchMahasiswa);
          cusEvent.$off('ReloadData', this.getMahasiswa);
        }
    }
</script>

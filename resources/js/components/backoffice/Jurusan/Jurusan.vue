<template>
    <div class="container">
      <div v-if="this.$gate.isWarek()">
        <div class="row mt-4">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Pengelolaan Jurusan</h3>
                <div class="card-tools">
                  <button class="btn btn-primary" @click="newModal"><i class="fas fa-plus-square"></i> Tambah Baru</button>
                  <button @click="exportJurusan('Jurusan.pdf')" class="btn btn-danger"><i class="fas fa-file-pdf"></i></button>
                  <button @click="exportJurusan('Jurusan.xlsx')" class="btn btn-success"><i class="fas fa-file-excel"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>No</th>
                    <th>Nama Jurusan</th>
                    <th>Kaprodi</th>
                    <th>Jenis</th>
                    <th>Program</th>
                    <th>Fakultas</th>
                    <th>Gelar</th>
                    <th width="17%" class="text-center">Aksi</th>
                  </tr>
                  <tr v-for="(data, index) in jurusan.data" :key="index">
                    <td>{{jurusan.meta.from+index}}</td>
                    <td>{{data.nama_jurusan}}</td>
                    <td>{{data.user.name}}</td>
                    <td>{{data.jenis_pendidikan}}</td>
                    <td>{{data.program}}</td>
                    <td>{{data.fakultas}}</td>
                    <td>{{data.gelar}}</td>
                    <td class="text-center">
                      <button  @click="previewTemplate(data)" class="btn btn-link"><i  class="fas fa-eye text-darkblue"></i></button>
                      <button @click="editModal(data)" class="btn btn-link"><i  class="fas fa-edit"></i></button>
                      <button @click="deleteJurusan(data.id)" class="btn btn-link"><i  class="fas fa-trash text-red"></i></button>
                    </td>
                  </tr>

                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination v-if="!searching" :data="jurusan" align="center" @pagination-change-page="getJurusan"></pagination>
                  <pagination v-else :data="jurusan" align="center" @pagination-change-page="searchJurusan"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <div id="modalForm" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!editMode" class="modal-title">Tambah Jurusan</h5>
                        <h5 v-show="editMode" class="modal-title">Ubah Jurusan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateJurusan() : createJurusan()">
                    <div class="modal-body">
                        <div class="row">
                          <div class="col-md-6">    
                            <div class="form-group">
                              <label for="inputNamaJurusan">Nama Jurusan</label>
                              <input v-model="form.nama_jurusan" type="text" name="nama_jurusan"
                                class="form-control" placeholder="Nama Jurusan" :class="{ 'is-invalid': form.errors.has('nama_jurusan') }" id="inputNamaJurusan">
                              <has-error :form="form" field="nama_jurusan"></has-error>
                            </div>
                          </div>
                          <div class="col-md-6">    
                            <div class="form-group">
                              <label for="inputNamaJurusanEn">Nama Jurusan (En) </label>
                              <input v-model="form.nama_jurusan_en" type="text" name="nama_jurusan_en"
                                class="form-control" placeholder="Nama Jurusan (En)" :class="{ 'is-invalid': form.errors.has('nama_jurusan_en') }" id="inputNamaJurusanEn">
                              <has-error :form="form" field="nama_jurusan_en"></has-error>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputKaprodi">Kepala Program Studi</label>
                          <select name="id_account" v-model="form.id_account" id="inputKaprodi" class="form-control" :class="{
                            'is-invalid': form.errors.has('id_account')}">
                            <option value="" selected>Choose Kaprodi</option>
                            <option v-for="data in kaprodi.data" :key="data.id" v-bind:value="data.id">{{ data.name }}</option>
                          </select>
                          <has-error :form="form" field="id_account"></has-error>
                        </div>
                        <div class="form-group">
                          <label for="inputJenisPendidikan">Jenis Pendidikan</label>
                          <select name="jenis_pendidikan" v-model="form.jenis_pendidikan" id="inputJenisPendidikan" class="form-control" :class="{
                            'is-invalid': form.errors.has('jenis_pendidikan')}">
                            <option value="" selected>Choose Jenis Pendidikan</option>
                            <option value="Akademik">Akademik</option>
                            <option value="Vokasi">Vokasi</option>
                            <option value="Profesi">Profesi</option>
                          </select>
                          <has-error :form="form" field="jenis_pendidikan"></has-error>
                        </div>
                        <div class="form-group">
                          <label for="inputProgram">Program</label>
                          <select name="program" v-model="form.program" id="inputProgram" class="form-control" :class="{
                            'is-invalid': form.errors.has('program')}">
                            <option value="" selected>Choose Program</option>
                            <option value="Diploma">Diploma</option>
                            <option value="Sarjana">Sarjana</option>
                            <option value="Magister">Magister</option>
                            <option value="Doktor">Doktor</option>
                            <option value="Profesi">Profesi</option>
                            <option value="Doktor">Spesialis</option>
                          </select>
                          <has-error :form="form" field="program"></has-error>
                        </div>
                        <div class="form-group">
                          <label for="inputFakultas">Fakultas</label>
                          <select name="fakultas" v-model="form.fakultas" id="inputFakultas" class="form-control" :class="{
                            'is-invalid': form.errors.has('fakultas')}">
                            <option value="" selected>Choose Fakultas</option>
                            <option value="Fakultas Ekonomi">Fakultas Ekonomi</option>
                            <option value="Fakultas Hukum">Fakultas Hukum</option>
                            <option value="Fakultas Teknik">Fakultas Teknik</option>
                          </select>
                          <has-error :form="form" field="fakultas"></has-error>
                        </div>
                        <div class="form-group">
                          <label for="inputGelar">Gelar</label>
                          <input v-model="form.gelar" type="text" name="gelar"
                            class="form-control" placeholder="Gelar yang diberikan" :class="{ 'is-invalid': form.errors.has('gelar') }" id="inputGelar">
                          <has-error :form="form" field="gelar"></has-error>
                        </div>
                        <div class="form-group">
                          <label for="inputPersyaratan">Persyaratan</label>
                          <textarea v-model="form.persyaratan" name="persyaratan"
                            class="form-control" placeholder="Persyaratan Penerimaan" :class="{ 'is-invalid': form.errors.has('persyaratan') }" id="inputPersyaratan">
                          </textarea>
                          <has-error :form="form" field="persyaratan"></has-error>
                        </div>
                        <div class="form-group">
                          <label for="inputPersyaratanEn">Persyaratan (En)</label>
                          <textarea v-model="form.persyaratan_en" name="persyaratan_en"
                            class="form-control" placeholder="Persyaratan Penerimaan (En)" :class="{ 'is-invalid': form.errors.has('persyaratan_en') }" id="inputPersyaratanEn">
                          </textarea>
                          <has-error :form="form" field="persyaratan_en"></has-error>
                        </div>
                        <div class="form-group">
                          <label for="inputPenilaian">Penilaian</label>
                          <textarea v-model="form.penilaian" name="penilaian"
                            class="form-control" placeholder="Sistem Penilaian" :class="{ 'is-invalid': form.errors.has('penilaian') }" id="inputPenilaian">
                          </textarea>  
                          <has-error :form="form" field="penilaian"></has-error>
                        </div>
                        <div class="form-group">
                          <label for="inputTemplate">Template Pdf</label>
                          <div class="input-group">
                              <div class="custom-file">
                                  <input type="file" class="custom-file-input" name="template" id="inputTemplate" @change="updateTemplate" placeholder="Template Pdf" :class="{ 'is-invalid': form.errors.has('template') }" accept="application/pdf">
                                  <label class="custom-file-label" for="inputBukti">{{nama_file}}</label>
                                  <has-error :form="form" field="template"></has-error>
                              </div>
                          </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button v-show="!editMode" type="submit" class="btn btn-primary">Tambah</button>
                      <button v-show="editMode" type="submit" class="btn btn-success">Simpan</button>
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="previewTemplate" class="modal fade" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <embed  :src="'storage/data/template/'+form.template" width="100%" height="600" type="application/pdf">
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
            jurusan : {},
            kaprodi : {},
            searching: false,
            editMode:false,
            nama_file:'Choose File',
            form : new Form({
              id:'',
              id_account:'',
              nama_jurusan:'',
              nama_jurusan_en:'',
              jenis_pendidikan:'',
              program:'',
              fakultas:'',
              gelar:'',
              persyaratan:'',
              persyaratan_en:'',
              penilaian:'',
              template:''
            })
          }
        },
        methods:{
          newModal(){
            this.editMode = false;
            this.form.reset ();
            this.form.clear ();
            this.nama_file = 'Choose File';
            $('#modalForm').modal('show');
          },
          editModal(jurusan){
            this.editMode = true;
            this.form.reset ();
            this.form.clear ();
            $('#modalForm').modal('show');
            this.form.fill(jurusan);
            this.nama_file = 'Choose File';
            this.form.template = '';
          },
          getKaprodi(){
            if(this.$gate.isWarek()){
              this.$Progress.start();
              axios.get('api/user/select/kaprodi')
              .then(response => {
                this.kaprodi = response.data;
                this.$Progress.finish();
              })
              .catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Gagal memuat data kaprodi'
              });
              });
            }
          },
          getJurusan(page = 1) {
            if(this.$gate.isWarek()){
              this.$Progress.start();
              axios.get('api/jurusan?page=' + page)
              .then(response => {
                this.jurusan = response.data;
                this.$Progress.finish();
              })
              .catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Gagal memuat data jurusan'
              });
              });
            }
          },
          createJurusan(){
            this.$Progress.start();
            this.form.post('api/jurusan')
            .then(() => {
              cusEvent.$emit('ReloadData');
              $('#modalForm').modal('hide');
              toast.fire({
                type: 'success',
                title: 'Berhasil menambah jurusan'
              });
              this.$Progress.finish();
            })
            .catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Gagal menambah jurusan'
              });
            });
          },
          updateJurusan(){
            this.$Progress.start();
            this.form.put('api/jurusan/'+this.form.id)
            .then(() => {
              cusEvent.$emit('ReloadData');
              $('#modalForm').modal('hide');
              toast.fire({
                type: 'success',
                title: 'Berhasil mengubah jurusan'
              });
              this.$Progress.finish();
            }).catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Gagal mengubah jurusan'
              });
            });
          },
          deleteJurusan(id){
            swal.fire({
              title: 'Anda yakin?',
              text: "Anda tidak akan dapat mengembalikan ini!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
              if (result.value){
                this.form.delete('api/jurusan/'+id)
                .then(() => {
                  this.$Progress.start();
                  swal.fire(
                    'Terhapus!',
                    'Jurusan berhasil dihapus.',
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
                    text: 'Jurusan gagal dihapus'
                  });
                });
              }
            });
          },
          exportJurusan(fileType){
            this.$Progress.start();
            axios({
              url: 'api/jurusan/export',
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
              title: 'Gagal ekspor jurusan'
              });
            })
          },
          searchJurusan(page = 1){
            let query = this.$parent.search;
            if(this.$parent.search != ''){
              this.$Progress.start();
              this.searching= true;
              axios.get('api/jurusan/find?q=' + query + '&page='+ page)
              .then((response) => {
                  this.jurusan = response.data
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
          updateTemplate(e){
            let file = e.target.files[0];
            let validFileTypes = ['application/pdf'];
            this.nama_file = file['name'];
            if(validFileTypes.includes(file['type'])){
              let reader = new FileReader();
              reader.onloadend = (file) => {
              this.form.template = reader.result;
              }
              reader.readAsDataURL(file);
            }else{
              this.nama_file = 'Choose File';
              e.target.value = ''
                swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'File yang diupload harus bertipe pdf'
              });
            }
          },
          previewTemplate(jurusan){
            this.form.fill(jurusan);
            $('#previewTemplate').modal('show');
          },
        },
        created() {
          this.$parent.search = '';
          this.getJurusan();
          this.getKaprodi();
          cusEvent.$on('Searching', this.searchJurusan);
          cusEvent.$on('ReloadData', this.getJurusan, this.getKaprodi);
        },
        beforeDestroy(){
          cusEvent.$off('Searching', this.searchJurusan);
          cusEvent.$off('ReloadData', this.getJurusan, this.getKaprodi);
        }
    }
</script>

<template>
    <div class="container">
      <div v-if="this.$gate.isKaprodi()">
        <div class="row mt-4">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Pengelolaan Kompetensi Wajib</h3>
                <div class="card-tools">
                  <button class="btn btn-primary" @click="newModal"><i class="fas fa-plus-square"></i> Tambah Baru</button>
                  <button @click="exportKompetensiWajib('KompetensiWajib.pdf')" class="btn btn-danger"><i class="fas fa-file-pdf"></i></button>
                  <button @click="exportKompetensiWajib('KompetensiWajib.xlsx')" class="btn btn-success"><i class="fas fa-file-excel"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>No</th>
                    <th>Nama Kompetensi</th>
                    <th width="15%" class="text-center">Aksi</th>
                  </tr>
                  <tr v-for="(data, index) in kompetensiWajib.data" :key="index">
                    <td>{{kompetensiWajib.meta.from+index}}</td>
                    <td>{{data.nama_kompetensi_wajib}}</td>
                    <td class="text-center">
                        <button @click="editModal(data)" class="btn btn-link"><i  class="fas fa-edit"></i></button>
                        <button @click="deleteKompetensiWajib(data.id)" class="btn btn-link"><i  class="fas fa-trash text-red"></i></button>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination v-if="!searching" :data="kompetensiWajib" align="center" @pagination-change-page="getKompetensiWajib"></pagination>
                  <pagination v-else :data="kompetensiWajib" align="center" @pagination-change-page="searchKompetensiWajib"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <div id="modalForm" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!editMode" class="modal-title">Tambah Kompetensi Wajib</h5>
                        <h5 v-show="editMode" class="modal-title">Ubah Kompetensi Wajib</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateKompetensiWajib() : createKompetensiWajib()">
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="inputNamaKompetensi">Nama Kompetensi</label>
                          <input v-model="form.nama_kompetensi_wajib" type="text" name="nama_kompetensi_wajib"
                            class="form-control" placeholder="Nama Kompetensi" :class="{ 'is-invalid': form.errors.has('nama_kompetensi_wajib') }" id="inputNamaKompetensi">
                          <has-error :form="form" field="nama_kompetensi_wajib"></has-error>
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
            kompetensiWajib : {},
            searching:false,
            editMode:false,
            form : new Form({
              id:'',  
              id_jurusan:'',  
              nama_kompetensi_wajib:''
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
          editModal(kompetensiWajib){
            this.editMode = true;
            this.form.reset ();
            this.form.clear ();
            $('#modalForm').modal('show');
            this.form.fill(kompetensiWajib);
          },
          getKompetensiWajib(page = 1) {
            if(this.$gate.isKaprodi()){
              this.$Progress.start();
              axios.get('api/kompetensi-wajib?page=' + page)
              .then(response => {
                this.kompetensiWajib = response.data;
                this.$Progress.finish();
              })
              .catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Gagal memuat kompetensi wajib'
              });
              });
            }
          },
          createKompetensiWajib(){
            this.$Progress.start();
            this.form.post('api/kompetensi-wajib')
            .then(() => {
              cusEvent.$emit('ReloadData');
              $('#modalForm').modal('hide');
              toast.fire({
                type: 'success',
                title: 'Kompetensi Wajib berhasil dibuat'
              });
              this.$Progress.finish();
            })
            .catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Kompetensi Wajib gagal dibuat'
              });
            });
          },
          updateKompetensiWajib(){
            this.$Progress.start();
            this.form.put('api/kompetensi-wajib/'+this.form.id)
            .then(() => {
              cusEvent.$emit('ReloadData');
              $('#modalForm').modal('hide');
              toast.fire({
                type: 'success',
                title: 'Kompetensi Wajib berhasil diubah'
              });
              this.$Progress.finish();
            }).catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Kompetensi Wajib gagal diubah'
              });
            });
          },
          deleteKompetensiWajib(id){
            swal.fire({
              title: 'Anda yakin?',
              text: "Anda tidak akan dapat mengembalikan ini!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, hapus!'
            }).then((result) => {
              if (result.value){
                this.form.delete('api/kompetensi-wajib/'+id)
                .then(() => {
                  this.$Progress.start();
                  swal.fire(
                    'Deleted!',
                    'Kompetensi Wajib berhasil dihapus.',
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
                    text: 'Kompetensi Wajib gagal dihapus'
                  });
                });
              }
            });
          },
          exportKompetensiWajib(fileType){
            this.$Progress.start();
            axios({
              url: 'api/kompetensi-wajib/export',
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
              title: 'Kompetensi Wajib gagal diekspor'
              });
            })
          },
          searchKompetensiWajib(page = 1){
            let query = this.$parent.search;
            if(this.$parent.search != ''){
              this.$Progress.start();
              this.searching= true;
              axios.get('api/kompetensi-wajib/find?q=' + query + '&page='+ page)
              .then((response) => {
                  this.kompetensiWajib = response.data
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
          this.getKompetensiWajib();
          cusEvent.$on('Searching', this.searchKompetensiWajib);
          cusEvent.$on('ReloadData', this.getKompetensiWajib);
        },
        beforeDestroy(){
          cusEvent.$off('Searching', this.searchKompetensiWajib);
          cusEvent.$off('ReloadData', this.getKompetensiWajib);
        }
    }
</script>

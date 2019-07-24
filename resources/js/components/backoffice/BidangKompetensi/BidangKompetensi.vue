<template>
    <div class="container">
      <div v-if="this.$gate.isWarek()">
        <div class="row mt-4">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Pengelolaan Bidang Kompetensi</h3>
                <div class="card-tools">
                  <button class="btn btn-primary" @click="newModal"><i class="fas fa-plus-square"></i> Tambah Data</button>
                  <button @click="exportBidangKompetensi('BidangKompetensi.pdf')" class="btn btn-danger"><i class="fas fa-file-pdf"></i></button>
                  <button @click="exportBidangKompetensi('BidangKompetensi.xlsx')" class="btn btn-success"><i class="fas fa-file-excel"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>No</th>
                    <th>Nama Bidang</th>
                    <th>Nama Bidang(Inggris)</th>
                    <th width="12%" class="text-center">Aksi</th>
                  </tr>
                  <tr v-for="(data, index) in bidangKompetensi.data" :key="index">
                    <td>{{bidangKompetensi.meta.from+index}}</td>
                    <td>{{data.nama_bidang}}</td>
                    <td>{{data.nama_bidang_en}}</td>
                    <td class="text-center">
                        <button @click="editModal(data)" class="btn btn-link"><i  class="fas fa-edit"></i></button>
                        <button v-if="data.id != 1" @click="deleteBidangKompetensi(data.id)" class="btn btn-link"><i  class="fas fa-trash text-red"></i></button>
                    </td>
                  </tr>

                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination v-if="!searching" :data="bidangKompetensi" align="center" @pagination-change-page="getBidangKompetensi"></pagination>
                  <pagination v-else :data="bidangKompetensi" align="center" @pagination-change-page="searchBidangKompetensi"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <div id="modalForm" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!editMode" class="modal-title">Tambah Bidang Kompetensi</h5>
                        <h5 v-show="editMode" class="modal-title">Ubah Bidang Kompetensi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateBidangKompetensi() : createBidangKompetensi()">
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="inputNamaBidang">Nama Bidang Kompetensi</label>
                          <input v-model="form.nama_bidang" type="text" name="nama_bidang"
                            class="form-control" placeholder="Nama Bidang" :class="{ 'is-invalid': form.errors.has('nama_bidang') }" id="inputNamaBidang">
                          <has-error :form="form" field="nama_bidang"></has-error>
                        </div>
                        <div class="form-group">
                          <label for="inputNamaBidangEn">Nama Bidang Kompetensi(Inggris)</label>
                          <input v-model="form.nama_bidang_en" type="text" name="nama_bidang_en"
                            class="form-control" placeholder="Nama Bidang English" :class="{ 'is-invalid': form.errors.has('nama_bidang_en') }" id="inputNamaBidangEn">
                          <has-error :form="form" field="nama_bidang_en"></has-error>
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
            bidangKompetensi : {},
            searching: false,
            editMode:false,
            form : new Form({
              id:'',  
              nama_bidang:'',
              nama_bidang_en:''
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
          editModal(bidangKompetensi){
            this.editMode = true;
            this.form.reset ();
            this.form.clear ();
            $('#modalForm').modal('show');
            this.form.fill(bidangKompetensi);
          },
          getBidangKompetensi(page = 1) {
            if(this.$gate.isWarek()){
              this.$Progress.start();
              axios.get('api/bidang-kompetensi?page=' + page)
              .then(response => {
                this.bidangKompetensi = response.data;
                this.$Progress.finish();
              })
              .catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Gagal memuat bidang kompetensi'
              });
              });
            }
          },
          createBidangKompetensi(){
            this.$Progress.start();
            this.form.post('api/bidang-kompetensi')
            .then(() => {
              cusEvent.$emit('ReloadData');
              $('#modalForm').modal('hide');
              toast.fire({
                type: 'success',
                title: 'Bidang Kompetensi berhasil dibuat'
              });
              this.$Progress.finish();
            })
            .catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Bidang Kompetensi gagal dibuat'
              });
            });
          },
          updateBidangKompetensi(){
            this.$Progress.start();
            this.form.put('api/bidang-kompetensi/'+this.form.id)
            .then(() => {
              cusEvent.$emit('ReloadData');
              $('#modalForm').modal('hide');
              toast.fire({
                type: 'success',
                title: 'Bidang Kompetensi berhasil diubah'
              });
              this.$Progress.finish();
            }).catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Bidang Kompetensi gagal diubah'
              });
            });
          },
          deleteBidangKompetensi(id){
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
                this.form.delete('api/bidang-kompetensi/'+id)
                .then(() => {
                  this.$Progress.start();
                  swal.fire(
                    'Deleted!',
                    'Bidang Kompetensi berhasil dihapus.',
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
                    text: 'Bidang Kompetensi gagal dihapus'
                  });
                });
              }
            });
          },
          exportBidangKompetensi(fileType){
            this.$Progress.start();
            axios({
              url: 'api/bidang-kompetensi/export',
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
              title: 'Gagal ekspor Bidang Kompetensi'
              });
            })
          },
          searchBidangKompetensi(page = 1){
            let query = this.$parent.search;
            if(this.$parent.search != ''){
              this.$Progress.start();
              this.searching= true;
              axios.get('api/bidang-kompetensi/find?q=' + query + '&page='+ page)
              .then((response) => {
                  this.bidangKompetensi = response.data
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
          this.getBidangKompetensi();
          cusEvent.$on('Searching', this.searchBidangKompetensi);
          cusEvent.$on('ReloadData', this.getBidangKompetensi);
        },
        beforeDestroy(){
          cusEvent.$off('Searching', this.searchBidangKompetensi);
          cusEvent.$off('ReloadData', this.getBidangKompetensi);
        }
    }
</script>

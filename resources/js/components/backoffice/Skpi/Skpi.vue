<template>
    <div class="container">
        <div v-if="$gate.isWarek() || $gate.isKaprodi() || $gate.isAkademik()">
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Pengelolaan SKPI</h3>
                            <div class="card-tools">
                            <button @click="exportSkpi('Skpi.pdf')" class="btn btn-danger"><i class="fas fa-file-pdf"></i></button>
                            <button @click="exportSkpi('Skpi.xlsx')" class="btn btn-success"><i class="fas fa-file-excel"></i></button>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                          <table class="table table-hover">
                            <tbody><tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Npm</th>
                                <th>Jurusan</th>
                                <th class="text-center">Poin Skpi</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Pratinjau</th>
                                <th v-if="!$gate.isAkademik()">Kompetensi</th>
                                <th v-if="$gate.isWarek()">Aksi</th>
                            </tr>
                            <tr v-for="(data, index) in skpi.data" :key="index">
                                <td class="align-middle">{{skpi.meta.from+index}}</td>
                                <td class="align-middle">{{data.nama}}</td>
                                <td class="align-middle">{{data.npm}}</td>
                                <td class="align-middle">{{data.nama_jurusan}}</td>
                                <td class="align-middle text-center">{{data.point_skpi}}</td>
                                <td class="text-center align-middle">
                                  <span v-if="data.status == 'progress'" class="badge bg-orange text-white p-1">{{data.status}}</span>
                                  <span v-else class="badge bg-success text-white p-1">{{data.status}}</span>
                                </td>
                                <td class="text-center align-middle">
                                  <div v-if="$gate.isKaprodi()">  
                                    <button v-if="data.status == 'published'" @click="viewSkpi(data.file)" class="btn btn-link btn-lg">
                                      <i  class="fas fa-file-pdf"></i>
                                    </button>
                                    <button v-else @click="publishSkpi(data.id, data.id_mahasiswa)" class="btn btn-sm btn-outline-success">
                                      Publish
                                    </button>
                                  </div>
                                  <div v-else>
                                    <button v-if="data.status == 'published'" @click="viewSkpi(data.file)" class="btn btn-link btn-lg">
                                      <i  class="fas fa-file-pdf"></i>
                                    </button>
                                    <i v-else class="fas fa-eye-slash"></i>
                                  </div>
                                </td>
                                <td v-if="!$gate.isAkademik()" class="text-center align-middle">
                                  <router-link :to="{name:'skpi-kompetensi', params:{ id:data.id_mahasiswa, nama:data.nama, status:data.status}}" class="btn btn-link btn-lg">
                                    <i class="fas fa-sign-in-alt text-teal"></i>
                                  </router-link>
                                </td>
                                <td v-if="$gate.isWarek()" class="text-center align-middle">
                                  <button class="btn btn-link btn-sm" @click="deleteSkpi(data.id)">
                                    <i  class="fas fa-trash text-red"></i>
                                  </button>
                                </td>
                            </tr>
                            </tbody>
                          </table>
                        </div>
                        <div class="card-footer">
                            <pagination v-if="!searching"  :data="skpi" align="center" @pagination-change-page="getSkpi"></pagination>
                            <pagination v-else  :data="skpi" align="center" @pagination-change-page="searchSkpi"></pagination>
                        </div>
                    </div>
                </div>
            </div>
            <div id="previewSkpi" class="modal fade" tabindex="-1" role="dialog">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <embed v-if="file != ''"  :src="'storage/data/skpi/'+file" width="100%" height="600" type="application/pdf">
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
                searching: false,
                skpi:{},
                kompetensi:{},
                file:'',
            }
        },
         methods:{
          getSkpi(page = 1) {
            if(this.$gate.isWarek() || this.$gate.isKaprodi() || this.$gate.isAkademik()){
              this.$Progress.start();
              axios.get('api/skpi?page=' + page)
              .then(response => {
                this.skpi = response.data;
                this.$Progress.finish();
              })
              .catch(() => {
                this.$Progress.fail();
                toast.fire({
                  type: 'error',
                  title: 'Gagal memuat data SKPI'
                });
              });
            }
          },
          deleteSkpi(id){
            if(this.$gate.isWarek()){
              swal.fire({
                title: 'Anda yakin?',
                text: "Anda tidak akan dapat mengembalikan ini!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
              }).then((result) => {
                if (result.value){
                  axios.delete('api/skpi/'+id)
                  .then(() => {
                    this.$Progress.start();
                    swal.fire(
                      'Deleted!',
                      'Berhasil menghapus SKPI.',
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
                      text: 'Gagal menghapus SKPI'
                    });
                  });
                }
              });
            }     
          },
          exportSkpi(fileType){
            this.$Progress.start();
            axios({
              url: 'api/skpi/export',
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
              title: 'Gagal ekspor data SKPI'
              });
            })
          },
          searchSkpi(page = 1){
            let query = this.$parent.search;
            if(this.$parent.search != ''){
              this.$Progress.start();
              this.searching= true;
              axios.get('api/skpi/find?q=' + query + '&page='+ page)
              .then((response) => {
                  this.skpi = response.data
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
          publishSkpi(id,id_mahasiswa){
            if(this.$gate.isKaprodi()){
              this.$Progress.start();
              axios.get('api/skpi/publish/'+ id)
              .then( () => {
                cusEvent.$emit('ReloadData');
                this.$Progress.finish();
                toast.fire({
                    type: 'success',
                    title: 'Berhasil menerbitkan SKPI'
                });
                axios.get('api/mahasiswa/point/'+id_mahasiswa)
                .catch(() => {
                  toast.fire({
                      type: 'error',
                      title: 'Gagal menghitung poin'
                  });
                });
                
              })
              .catch(() => {
                  this.$Progress.fail();
                  toast.fire({
                      type: 'error',
                      title: 'Gagal menerbitkan SKPI'
                  });
              });
            }  
          },
          viewSkpi(file){
            this.file = file;
            $('#previewSkpi').modal('show');
          }
        },
        created() {
          this.$parent.search = '';
          this.getSkpi();
          cusEvent.$on('Searching', this.searchSkpi);
          cusEvent.$on('ReloadData', this.getSkpi);
        },
        beforeDestroy(){
          cusEvent.$off('Searching', this.searchSkpi);
          cusEvent.$off('ReloadData', this.getSkpi);
        }
    }
</script>

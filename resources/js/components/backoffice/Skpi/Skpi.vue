<template>
    <div class="container">
        <div v-if="this.$gate.isWarek() || this.$gate.isKaprodi() || this.$gate.isAkademik()">
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Manage SKPI</h3>
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
                                <th>Point Skpi</th>
                                <th>Status</th>
                                <th>Preview</th>
                                <th>Kompetensi</th>
                                <th>Action</th>
                            </tr>
                            <tr v-for="(data, index) in skpi.data" :key="index">
                                <td class="align-middle">{{skpi.meta.from+index}}</td>
                                <td class="align-middle">{{data.nama}}</td>
                                <td class="align-middle">{{data.npm}}</td>
                                <td class="align-middle">{{data.nama_jurusan}}</td>
                                <td class="text-center">{{data.point_skpi}}</td>
                                <td class="align-middle">
                                  <span v-if="data.status == 'progress'" class="badge bg-orange text-white p-1">{{data.status}}</span>
                                  <span v-else class="badge bg-success text-white p-1">{{data.status}}</span>
                                </td>
                                <td class="text-center align-middle">
                                  <button v-if="data.status == 'published'" @click="viewModal(data)" class="btn btn-link btn-lg">
                                    <i  class="fas fa-file-pdf"></i>
                                  </button>
                                  <button v-else @click="publishSkpi(data.id)" class="btn btn-sm btn-outline-success">
                                    Publish
                                  </button>
                                </td>
                                <td class="text-center align-middle">
                                  <router-link :to="{name:'skpi-kompetensi', params:{ id:data.id_mahasiswa, nama:data.nama}}" class="btn btn-link btn-lg">
                                    <i class="fas fa-sign-in-alt text-teal"></i>
                                  </router-link>
                                </td>
                                <td class="text-center align-middle">
                                  <button class="btn btn-link btn-sm" @click="deleteSkpi(data.id)">
                                    <i  class="fas fa-trash text-red"></i>
                                  </button>
                                </td>
                            </tr>

                            </tbody></table>
                        </div>
                        <div class="card-footer">
                            <pagination v-if="!searching"  :data="skpi" align="center" @pagination-change-page="getSkpi"></pagination>
                            <pagination v-else  :data="skpi" align="center" @pagination-change-page="searchSkpi"></pagination>
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
                searching: false,
                skpi:{},
                kompetensi:{}
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
                  title: 'Load data skpi failed'
                });
              });
            }
          },
          deleteSkpi(id){
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
                this.form.delete('api/skpi/'+id)
                .then(() => {
                  this.$Progress.start();
                  swal.fire(
                    'Deleted!',
                    'Skpi has been deleted.',
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
                    text: 'Skpi delete failed'
                  });
                });
              }
            });
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
              title: 'Data Skpi export failed'
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
          publishSkpi(id){
            this.$Progress.start();
            axios.get('api/skpi/publish/'+ id)
            .then( () => {
              cusEvent.$emit('ReloadData');
              toast.fire({
                  type: 'success',
                  title: 'Skpi publish successfull'
              });
              this.$Progress.finish();
            })
            .catch(() => {
                this.$Progress.fail();
                toast.fire({
                    type: 'error',
                    title: 'Skpi publish failed'
                });
            });
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

<template>
    <div>
        <div class="card-header bg-white">
          <h3 class="card-title">Manage Kompetensi</h3>
          <div class="card-tools">
              <button class="btn btn-primary" @click="newModal"><i class="fas fa-plus-square"></i> Add New</button>
              <button @click="exportKompetensi('Kompetensi.pdf')" class="btn btn-danger"><i class="fas fa-file-pdf"></i></button>
              <button @click="exportKompetensi('Kompetensi.xlsx')" class="btn btn-success"><i class="fas fa-file-excel"></i></button>
          </div>
        </div>
        <div class="card-body table-responsive p-0">
          <table class="table table-hover">
              <tbody><tr>
                <th>No</th>
                <th width="25%">Nama Kompetensi</th>
                <th>Bidang</th>
                <th>Tanggal Kegiatan</th>
                <th>Tingkat</th>
                <th>Peran</th>
                <th width="5%" class="text-center">Point</th>
                <th width="20%" class="text-center">Action</th>
                </tr>
                <tr v-for="(data, index) in kompetensi.data" :key="index">
                <td>{{kompetensi.meta.from+index}}</td>
                <td>{{data.nama_kompetensi}}</td>
                <td>{{data.bidang_kompetensi.nama_bidang}}</td>
                <td>{{data.tgl_mulai}} - {{data.tgl_selesai}}</td>
                <td>{{data.tingkat}}</td>
                <td>{{data.peran}}</td>
                <td class="text-center">{{data.point_kompetensi}}</td>
                <td class="text-center">
                    <button  @click="previewKompetensi(data)" class="btn btn-link"><i  class="fas fa-eye text-darkblue"></i></button>
                    <button  @click="editModal(data)" class="btn btn-link"><i  class="fas fa-edit"></i></button>
                    <button  @click="deleteKompetensi(data.id)" class="btn btn-link"><i  class="fas fa-trash text-red"></i></button>
                </td>
                </tr>
              </tbody>
          </table>
        </div>
        <div class="card-footer">
            <pagination v-if="!searching" :data="kompetensi" align="center" @pagination-change-page="getKompetensi"></pagination>
            <pagination v-else :data="kompetensi" align="center" @pagination-change-page="searchKompetensi"></pagination>
        </div>
        <div id="modalForm" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!editMode" class="modal-title">Add New Kompetensi</h5>
                        <h5 v-show="editMode" class="modal-title">Edit Kompetensi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateKompetensi() : createKompetensi()">
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="inputNamaKompetensi">Nama Kompetensi</label>
                          <input v-model="form.nama_kompetensi" type="text" name="nama_kompetensi"
                            class="form-control" placeholder="Nama Kompetensi" :class="{ 'is-invalid': form.errors.has('nama_kompetensi') }" id="inputNamaKompetensi">
                          <has-error :form="form" field="nama_kompetensi"></has-error>
                        </div>
                        <div class="form-group">
                          <label for="inputBidang">Bidang Kompetensi</label>
                          <select name="id_bidang" v-model="form.id_bidang" id="inputBidang" class="form-control" :class="{
                            'is-invalid': form.errors.has('id_bidang')}">
                            <option value="" selected>Choose Bidang</option>
                            <option v-for="data in bidang.data" :key="data.id" v-bind:value="data.id">{{ data.nama_bidang }}</option>
                          </select>
                          <has-error :form="form" field="id_bidang"></has-error>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label for="inputTglMulai">Tanggal Mulai</label><br>
                                  <date-picker v-model="form.tgl_mulai" lang="en"  name="tgl_mulai" value-type="format" id="inputTglMulai" class="form-control-file" 
                                  :class="{ 'is-invalid': form.errors.has('tgl_mulai') }"></date-picker>
                                  <has-error :form="form" field="tgl_mulai"></has-error>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label for="inputTglSelesai">Tanggal Selesai</label><br>
                                  <date-picker v-model="form.tgl_selesai" lang="en"  name="tgl_selesai" value-type="format" id="inputTglSelesai" class="form-control-file" 
                                  :class="{ 'is-invalid': form.errors.has('tgl_selesai') }"></date-picker>
                                  <has-error :form="form" field="tgl_selesai"></has-error>
                              </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="inputTingkat">Tingkat</label>
                              <select name="tingkat" v-model="form.tingkat" id="inputTingkat" class="form-control" :class="{
                                'is-invalid': form.errors.has('tingkat')}">
                                <option value="" selected>Choose Tingkat</option>
                                <option v-if="form.id_bidang != 1" value="Internasional">Internasional</option>
                                <option v-if="form.id_bidang != 1" value="Nasional">Nasional</option>
                                <option v-if="form.id_bidang != 1" value="Provinsi">Provinsi</option>
                                <option v-if="form.id_bidang != 1" value="Kota">Kota</option>
                                <option value="Universitas">Universitas</option>
                              </select>
                              <has-error :form="form" field="tingkat"></has-error>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="inputPeran">Peran</label>
                              <select name="peran" v-model="form.peran" id="inputPeran" class="form-control" :class="{
                                'is-invalid': form.errors.has('peran')}">
                                <option value="" selected>Choose Peran</option>
                                <option value="Ketua">Ketua</option>
                                <option value="Ketua Divisi">Ketua Divisi</option>
                                <option value="Anggota Panitia">Anggota Panitia</option>
                                <option value="Peserta">Peserta</option>
                                <option value="Juara 1">Juara 1</option>
                                <option value="Juara 2">Juara 2</option>
                                <option value="Juara 3">Juara 3</option>
                                <option value="Juara Harapan">Juara Harapan</option>
                              </select>
                              <has-error :form="form" field="peran"></has-error>
                            </div>
                          </div>
                        </div>  
                        <div class="form-group">
                          <label for="inputBukti">Bukti Scan Atau PDF</label>
                          <div class="input-group">
                              <div class="custom-file">
                                  <input type="file" class="custom-file-input" name="bukti" id="inputBukti" @change="updateFile" placeholder="Bukti scan atau pdf" :class="{ 'is-invalid': form.errors.has('bukti') }" accept="image/*,application/pdf">
                                  <label class="custom-file-label" for="inputBukti">{{nama_file}}</label>
                                  <has-error :form="form" field="bukti"></has-error>
                              </div>
                          </div>
                           <p>ukuran maksimal 2 MB</p>
                        </div>
                         <div v-if="form.id_bidang != 1" class="form-group">
                          <label for="inputKemampuan">Kemampuan</label>
                          <multiselect
                          class="form-control-file" 
                          id="inputKemampuan" 
                          name="kemampuan"
                          v-model="form.kemampuan" 
                          tag-placeholder="Add Kemampuan" 
                          placeholder="Search or Add Kemampuan" 
                          label="nama_kemampuan" 
                          track-by="nama_kemampuan" 
                          :options="options" 
                          :multiple="true" 
                          :taggable="true"
                           @tag="addKemampuan"
                          :class="{ 'is-invalid': form.errors.has('kemampuan') }"></multiselect>
                          <has-error :form="form" field="kemampuan"></has-error>
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
        <div id="previewKompetensi" class="modal fade" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div v-if="form.bukti.split('.')[1] == 'pdf'" class="modal-content">
              <embed  :src="'storage/data/kompetensi/pdf/'+form.bukti" width="100%" height="600" type="application/pdf">
            </div>
            <img v-else-if="form.bukti.split('.')[1] == 'jpg' || form.bukti.split('.')[1] == 'jpeg' || form.bukti.split('.')[1] == 'png'" :src="'storage/data/kompetensi/img/'+form.bukti">  
          </div>  
        </div>  
    </div>
</template>
<script>
export default {
    data(){
          return{
            kompetensi : {},
            bidang : {},
            status: 'unregister',
            nama_file :'Choose File',
            searching :false,
            editMode :false,
            options: [
              { nama_kemampuan: 'Komunikasi' },
              { nama_kemampuan: 'Organisasi' },
              { nama_kemampuan: 'Kepemimpinan' },
              { nama_kemampuan: 'Logika' },
              { nama_kemampuan: 'Usaha' },
              { nama_kemampuan: 'Kerjasama Kelompok' },
              { nama_kemampuan: 'Etika' },
            ],
            form : new Form({
              id:'',
              id_bidang:'',
              nama_kompetensi:'',
              tgl_mulai:'',
              tgl_selesai:'',
              tingkat:'',
              peran:'',
              bukti:'',
              kemampuan: [],
            }),
          }
        },
        methods:{
          previewKompetensi(kompetensi){
            this.form.fill(kompetensi);
            $('#previewKompetensi').modal('show');
          },
          newModal(){
            this.editMode = false;
            this.form.reset ();
            this.form.clear ();
            this.nama_file = 'Choose File';
            $('#modalForm').modal('show');
          },
          editModal(kompetensi){
            this.editMode = true;
            this.form.reset ();
            this.form.clear ();
            $('#modalForm').modal('show');
            this.form.fill(kompetensi);
            this.form.bukti='',
            this.nama_file = 'Choose File';
          },
          addKemampuan (newKemampuan) {
            let kemampuan = {
              nama_kemampuan: newKemampuan['nama_kemampuan']
            }
            this.options.push(kemampuan);
            this.form.kemampuan.push(kemampuan);  
          },
          getBidang(){
            if(this.$gate.isMahasiswa()){
              this.$Progress.start();
              axios.get('api/bidang-kompetensi/select')
              .then(response => {
                this.bidang = response.data;
                this.$Progress.finish();
              })
              .catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Load data bidang kompetensi failed'
              });
              });
            }
          },
          getKompetensi(page = 1) {
            if(this.$gate.isMahasiswa()){
              axios.get('api/kompetensi?page=' + page)
              .then(response => {
                this.kompetensi = response.data;
              })
              .catch(() => {
              toast.fire({
                type: 'error',
                title: 'Load data kompetensi failed'
              });
              });
            }
          },
          createKompetensi(){
            this.$Progress.start();
            this.form.post('api/kompetensi')
            .then(() => {
              cusEvent.$emit('ReloadData');
              $('#modalForm').modal('hide');
              toast.fire({
                type: 'success',
                title: 'Kompetensi created successfully'
              });
              this.$Progress.finish();
            })
            .catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Kompetensi create failed'
              });
            });
          },
          updateKompetensi(){
            this.$Progress.start();
            this.form.put('api/kompetensi/'+this.form.id)
            .then(() => {
              cusEvent.$emit('ReloadData');
              $('#modalForm').modal('hide');
              toast.fire({
                type: 'success',
                title: 'Kompetensi updated successfully'
              });
              this.$Progress.finish();
            }).catch(() => {
              this.$Progress.fail();
              toast.fire({
                type: 'error',
                title: 'Kompetensi update failed'
              });
            });
          },
          deleteKompetensi(id){
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
                this.form.delete('api/kompetensi/'+id)
                .then(() => {
                  this.$Progress.start();
                  swal.fire(
                    'Deleted!',
                    'Kompetensi has been deleted.',
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
                    text: 'Kompetensi delete failed'
                  });
                });
              }
            });
          },
          exportKompetensi(fileType){
            this.$Progress.start();
            axios({
              url: 'api/kompetensi/export',
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
              title: 'Kompetensi export failed'
              });
            })
          },
          searchKompetensi(page = 1){
            let query = this.$root.search;
            if(this.$root.search != ''){
              this.$Progress.start();
              this.searching= true;
              axios.get('api/kompetensi/find?q=' + query + '&page='+ page)
              .then((response) => {
                  this.kompetensi = response.data
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
          updateFile(e){
            let file = e.target.files[0];
            let validFileTypes = ['image/jpg', 'image/jpeg', 'image/png', 'application/pdf'];
            this.nama_file = file['name'];
            if(file['size'] < 2097152){   
                if(validFileTypes.includes(file['type'])){
                  let reader = new FileReader();
                  reader.onloadend = (file) => {
                  this.form.bukti = reader.result;
                  }
                  reader.readAsDataURL(file);
                }else{
                  this.nama_file = 'Choose File';
                  e.target.value = ''
                    swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'You are uploading file must be pdf or image'
                  });
                }
            }else{
                this.nama_file = 'Choose File';
                e.target.value = ''
                swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'You are uploading a large file! maximum is 2 MB'
              });
            }
          },
          getStatus(){
            if(this.$gate.isMahasiswa()){
                axios.get('api/skpi/cek')
                .then(response => {
                    this.status = response.data.status;
                    if (this.status != 'uncompleted' && this.status != 'unregister') {
                      this.getKompetensi();
                    }
                })
                .catch(() => {
                    toast.fire({
                        type: 'error',
                        title: 'Get status skpi failed'
                    });
                });
            }
          },
        },
        mounted(){
          this.$root.search = '';
          this.getStatus();
          this.getBidang();
          cusEvent.$on('Searching', this.searchKompetensi);
          cusEvent.$on('ReloadData', this.getKompetensi, this.getBidang);
        },
        beforeDestroy(){
          cusEvent.$off('Searching', this.searchKompetensi);
          cusEvent.$off('ReloadData', this.getKompetensi, this.getBidang);
        }
}
</script>
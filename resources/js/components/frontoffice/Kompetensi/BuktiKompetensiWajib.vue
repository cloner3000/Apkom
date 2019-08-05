<template>
    <div>
        <div class="card-header bg-white">
            <h3 class="card-title">Kompetensi Wajib Jurusan</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <tbody><tr>
                <th>No</th>
                <th>Nama Kompetensi</th>
                <th>Unggah</th>
                <th>Lihat</th>
                </tr>
                <tr v-for="(data, index) in kompetensiWajib.data" :key="index">
                    <td>{{kompetensiWajib.meta.from+index}}</td>
                    <td>{{data.nama_kompetensi_wajib}}</td>
                    <td>
                        <input type="file" v-on:input="updateFile($event,data)" accept="image/*,application/pdf">    
                    </td>
                    <td>
                        <button v-if="data.bukti_wajib" @click="previewBukti(data)" class="btn btn-link"><i  class="fas fa-eye text-darkblue"></i></button>
                        <button v-else class="btn btn-link"><i class="fas fa-times text-red"></i></button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <pagination v-if="!searching" :data="kompetensiWajib" align="center" @pagination-change-page="getKompetensiWajib"></pagination>
            <pagination v-else :data="kompetensiWajib" align="center" @pagination-change-page="searchKompetensiWajib"></pagination>
        </div>
        <div id="previewBukti" class="modal fade" tabindex="-1" role="dialog">   
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div v-if="form.bukti_wajib != null" class="modal-body">
                    <div v-if="form.bukti_wajib.split('.')[1] == 'pdf'" class="modal-content">
                        <embed  :src="'storage/data/kompetensi/pdf/'+form.bukti_wajib" width="100%" height="600" type="application/pdf">
                    </div>
                    <img v-else-if="form.bukti_wajib.split('.')[1] == 'jpg' || form.bukti_wajib.split('.')[1] == 'jpeg' || form.bukti_wajib.split('.')[1] == 'png'" :src="'storage/data/kompetensi/img/'+form.bukti_wajib">  
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                kompetensiWajib: {},
                status: 'unregister',
                searching :false,
                form : new Form({
                    id:'',
                    nama_kompetensi_wajib:'',
                    bukti_wajib:''
                })
            }
        },
        methods:{
            getKompetensiWajib(page = 1){
                if(this.$gate.isMahasiswa()){
                    this.$Progress.start();
                    axios.get('api/bukti-kompetensi-wajib?page=' + page)
                    .then(response => {
                        this.kompetensiWajib = response.data;
                        this.$Progress.finish();
                    })
                    .catch(() => {
                    this.$Progress.fail();
                    toast.fire({
                        type: 'error',
                        title: 'Load data kompetensi wajib kompetensi failed'
                    });
                    });
                }
            },
            updateFile: function(e, buktiKompetensiWajib) {
                this.form.fill(buktiKompetensiWajib);    
                let file = e.target.files[0];
                let validFileTypes = ['image/jpg', 'image/jpeg', 'image/png', 'application/pdf'];
                if(file['size'] < 2097152){   
                    if(validFileTypes.includes(file['type'])){
                        let reader = new FileReader();
                        reader.onloadend = (file) => {
                        this.form.bukti_wajib = reader.result;
                         this.uploadBukti();
                        }
                        reader.readAsDataURL(file);
                    }else{  
                        e.target.value = ''
                        swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'File yang diunggah harus berupa gambar atau pdf'
                        });
                    }
                }else{
                    e.target.value = ''
                    swal.fire({
                       type: 'error',
                        title: 'Oops...',
                        text: 'Ukuran file terlalu besar, maksimal 2mb'
                    });
                }
            },
            uploadBukti(){
                this.$Progress.start();
                this.form.put('api/bukti-kompetensi-wajib/'+this.form.id)
                .then(() => {
                    cusEvent.$emit('ReloadData');
                    toast.fire({
                        type: 'success',
                        title: 'Berhasil mengunggah bukti kompetensi'
                    });
                    this.$Progress.finish();
                }).catch(() => {
                    this.$Progress.fail();
                    toast.fire({
                        type: 'error',
                        title: 'Berhasil mengunggah bukti kompetensi'
                    });
                });
            },
            searchKompetensiWajib(page = 1){
                let query = this.$root.search;
                if(this.$root.search != ''){
                this.$Progress.start();
                this.searching= true;
                axios.get('api/bukti-kompetensi-wajib/find?q=' + query + '&page='+ page)
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
            },
            previewBukti(buktiKompetensiWajib){
                this.form.fill(buktiKompetensiWajib);
                $('#previewBukti').modal('show');
            },
            getStatus(){
                if(this.$gate.isMahasiswa()){
                    axios.get('api/skpi/cek')
                    .then(response => {
                        this.status = response.data.status;
                        if(this.status != 'unregister'){
                            this.getKompetensiWajib();
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
          cusEvent.$on('Searching', this.searchKompetensiWajib);
          cusEvent.$on('ReloadData', this.getKompetensiWajib);
        },
        created() {
          
        },
        beforeDestroy(){
          cusEvent.$off('Searching', this.searchKompetensiWajib);
          cusEvent.$off('ReloadData', this.getKompetensiWajib);
        }
    }
</script>
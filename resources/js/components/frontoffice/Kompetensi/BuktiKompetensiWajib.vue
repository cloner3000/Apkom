<template>
    <div v-if="this.$gate.isMahasiswa()">
        <div class="card-header bg-white">
            <h3 class="card-title">Kompetensi Wajib Jurusan</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <tbody><tr>
                <th>No</th>
                <th>Nama Kompetensi</th>
                <th>Upload</th>
                <th>View</th>
                </tr>
                <tr v-for="(data, index) in kompetensiWajib.data" :key="index">
                    <td>{{kompetensiWajib.meta.from+index}}</td>
                    <td>{{data.nama_kompetensi_wajib}}</td>
                    <td>
                        <input type="file" v-on:input="updateFile($event,data)" accept="image/*,application/pdf">    
                    </td>
                    <td>
                        <a v-if="data.bukti_wajib" href="#" @click="previewBukti(data)"><i  class="fas fa-eye text-darkblue"></i></a>
                        <i v-else class="fas fa-times text-red"></i>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div v-if="form.bukti_wajib">
            <div id="previewBukti" class="modal fade" tabindex="-99" role="dialog">
                <div  class="modal-dialog modal-dialog-centered" role="document">
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
                form : new Form({
                    id:'',
                    nama_kompetensi_wajib:'',
                    bukti_wajib:''
                })
            }
        },
        methods:{
            getKompetensiWajib(){
                if(this.$gate.isMahasiswa()){
                    this.$Progress.start();
                    axios.get('api/bukti-kompetensi-wajib')
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
                            text: 'You are uploading file must be pdf or image'
                        });
                    }
                }else{
                    e.target.value = ''
                    swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'You are uploading a large file! maximum is 2 MB'
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
                        title: 'Upload bukti kompetensi wajib successfully'
                    });
                    this.$Progress.finish();
                }).catch(() => {
                    this.$Progress.fail();
                    toast.fire({
                        type: 'error',
                        title: 'Upload bukti kompetensi wajib failed'
                    });
                });
            },
            previewBukti(buktiKompetensiWajib){
                this.form.fill(buktiKompetensiWajib);
                $('#previewBukti').modal('show');
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
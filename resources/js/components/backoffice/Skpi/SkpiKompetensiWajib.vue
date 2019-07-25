<template>
    <div class="container">
        <div v-if="this.$gate.isWarek() || this.$gate.isKaprodi()">
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="card-title">
                        <a href="javascript:history.go(-1)" class="btn btn-white"><i class="fas fa-arrow-left"></i></a>    
                            {{$route.params.nama}}
                        </h5>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody><tr>
                                <th>No</th>
                                <th>Nama Kompetensi</th>
                                <th width="10%" class="text-center">Bukti</th>
                                <th width="10%" class="text-center">Validasi</th>
                                </tr>
                                <tr v-for="(data, index) in kompetensi.data" :key="index">
                                <td class="align-middle">{{kompetensi.meta.from+index}}</td>
                                <td class="align-middle">{{data.nama_kompetensi_wajib}}</td>
                                <td width="10%" class="text-center align-middle">
                                    <button class="btn btn-link"  @click="previewBukti(data)"><i  class="text-grey fas fa-eye"></i></button>
                                </td>
                                <td width="10%" class="text-center align-middle">
                                    <label class="checkwrap">
                                      <input type="checkbox" :checked="data.active == 1" v-on:change="changeValidation(data)" v-bind:disabled="$route.params.status != 'progress' || !$gate.isKaprodi()" hidden>
                                        <span v-if="$route.params.status == 'progress' && $gate.isKaprodi()" class="checkmark"></span>
                                        <span v-else class="checkmark bg-dark cursor-default"></span>
                                    </label>
                                </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <pagination v-if="!searching" :data="kompetensi" align="center" @pagination-change-page="getKompetensi"></pagination>
                        <pagination v-else :data="kompetensi" align="center" @pagination-change-page="searchKompetensi"></pagination>
                    </div>
                </div>
                </div>
            </div>
            <div id="previewBukti" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div v-if="kompetensiSelect.bukti_wajib != null" class="modal-body">
                        <div v-if="kompetensiSelect.bukti_wajib.split('.')[1] == 'pdf'" class="modal-content">
                        <embed  :src="'storage/data/kompetensi/pdf/'+kompetensiSelect.bukti_wajib" width="100%" height="600" type="application/pdf">
                        </div>
                        <img v-else-if="kompetensiSelect.bukti_wajib.split('.')[1] == 'jpg' || kompetensiSelect.bukti_wajib.split('.')[1] == 'jpeg' || kompetensiSelect.bukti_wajib.split('.')[1] == 'png'" :src="'storage/data/kompetensi/img/'+kompetensiSelect.bukti_wajib">  
                    </div>
                </div>  
            </div>  
            <div id="pesanKompetensi" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title">
                                <i class="fas fa-message mr-1"></i>
                                Alasan Ditolak
                            </h5>
                        </div>
                        <form @submit.prevent="createPesan">
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-body p-0">
                                    <textarea v-model="form.pesan" name="pesan"
                                    class="form-control" placeholder="Masukan Alasan Ditolak" :class="{ 'is-invalid': form.errors.has('pesan') }" id="inputPesan" required>
                                    </textarea>
                                    <has-error :form="form" field="pesan"></has-error>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
            kompetensi:{},
            searching:false,
            kompetensiSelect:{},
            form: new Form({
                id:'',
                pesan:''
            })
        }
    },
    methods:{
        getKompetensi(page = 1){
            this.$Progress.start();
            axios.get('api/bukti-kompetensi-wajib/skpi/'+ this.$route.params.id + '?page=' + page)
            .then( response => {
                this.kompetensi = response.data;
                this.$Progress.finish();
            })
            .catch(() => {
                this.$Progress.fail();
                toast.fire({
                    type: 'error',
                    title: 'Load data kompetensi wajib failed'
                });
            });
        },
        changeValidation(data){
            if(this.$gate.isKaprodi()){
                this.$Progress.start();
                axios.put('api/bukti-kompetensi-wajib/skpi/validation/'+ data.id)
                .then( (response) => {
                    this.$Progress.finish();
                    if(response.data != 1){
                        this.form.fill(data);
                        $('#pesanKompetensi').modal('show');
                    }
                })
                .catch(() => {
                    this.$Progress.fail();
                    toast.fire({
                        type: 'error',
                        title: 'Unvalidation kompetensi wajib failed'
                    });
                });
            }    
        },
        searchKompetensi(page = 1){
            let query = this.$root.search;
            if(this.$root.search != ''){
                this.$Progress.start();
                this.searching= true;
                axios.get('api/bukti-kompetensi-wajib/skpi/'+ this.$route.params.id +'/find?q=' + query + '&page='+ page)
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
        previewBukti(data){
            this.kompetensiSelect = data;
            $('#previewBukti').modal('show');
        },
        createPesan(){   
            this.$Progress.start();     
            this.form.put('api/bukti-kompetensi-wajib/pesan')
            .then(() =>{
                $('#pesanKompetensi').modal('hide');
                toast.fire({
                    type: 'success',
                    title: 'Berhasil menyimpan pesan'
                });
                this.$Progress.finish();
            })
            .catch(() => {
                this.$Progress.fail();
                toast.fire({
                    type: 'error',
                    title: 'Gagal menyimpan pesan'
                });
            })
        },
    },
    mounted(){
        this.$root.search = '';
        this.getKompetensi();
        cusEvent.$on('Searching', this.searchKompetensi);
        cusEvent.$on('ReloadData', this.getKompetensi);
    },
    beforeDestroy(){
        cusEvent.$off('Searching', this.searchKompetensi);
        cusEvent.$off('ReloadData', this.getKompetensi);
    }
}
</script>

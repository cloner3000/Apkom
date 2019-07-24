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
                                <th>Bidang</th>
                                <th>Tanggal Kegiatan</th>
                                <th>Tingkat</th>
                                <th>Peran</th>
                                <th>Poin</th>
                                <th>Kemampuan</th>
                                <th>Bukti</th>
                                <th>Validasi</th>
                                </tr>
                                <tr v-for="(data, index) in kompetensi.data" :key="index">
                                <td class="align-middle">{{kompetensi.meta.from+index}}</td>
                                <td class="align-middle">{{data.nama_kompetensi}}</td>
                                <td class="align-middle">{{data.bidang_kompetensi.nama_bidang}}</td>
                                <td class="align-middle">{{data.tgl_mulai}} - {{data.tgl_selesai}}</td>
                                <td class="align-middle">{{data.tingkat}}</td>
                                <td class="align-middle">{{data.peran}}</td>
                                <td class="align-middle">{{data.point_kompetensi}}</td>
                                <td class="text-center align-middle">
                                    <button class="btn btn-link" @click="previewKemampuan(data.kemampuan)"><i  class="fas fa-external-link-alt"></i></button>
                                </td>
                                <td class="text-center align-middle">
                                    <button class="btn btn-link"  @click="previewBukti(data)"><i  class="text-grey fas fa-eye"></i></button>
                                </td>
                                <td class="text-center align-middle">
                                    <label class="checkwrap">
                                      <input type="checkbox" :checked="data.active == 1" v-on:change="changeValidation(data.id)" v-bind:disabled="$route.params.status != 'progress' || !$gate.isKaprodi()" hidden>
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
            <div id="previewKemampuan" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title">
                                <i class="fas fa-user-graduate mr-1"></i>
                                Kemampuan
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-body p-0">
                                    <ul class="nav nav-pills flex-column">
                                        <li v-for="(data, index) in kompetensiSelect" :key="index" class="nav-item">
                                            <span class="nav-link">{{data.nama_kemampuan}}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div id="previewBukti" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div v-if="kompetensiSelect.bukti != null" class="modal-body">
                        <div v-if="kompetensiSelect.bukti.split('.')[1] == 'pdf'" class="modal-content">
                        <embed  :src="'storage/data/kompetensi/pdf/'+kompetensiSelect.bukti" width="100%" height="600" type="application/pdf">
                        </div>
                        <img v-else-if="kompetensiSelect.bukti.split('.')[1] == 'jpg' || kompetensiSelect.bukti.split('.')[1] == 'jpeg' || kompetensiSelect.bukti.split('.')[1] == 'png'" :src="'storage/data/kompetensi/img/'+kompetensiSelect.bukti">  
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
            kompetensiSelect:{}
        }
    },
    methods:{
        getKompetensi(page = 1){
            this.$Progress.start();
            axios.get('api/kompetensi/skpi/'+ this.$route.params.id + '?page=' + page)
            .then( response => {
                this.kompetensi = response.data;
                this.$Progress.finish();
            })
            .catch(() => {
                this.$Progress.fail();
                toast.fire({
                    type: 'error',
                    title: 'Load data kompetensi failed'
                });
            });
        },
        changeValidation(id){
            if(this.$gate.isKaprodi()){
                this.$Progress.start();
                axios.put('api/kompetensi/skpi/validation/'+ id)
                .then( () => {
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                    toast.fire({
                        type: 'error',
                        title: 'Unvalidation kompetensi failed'
                    });
                });
            }    
        },
        searchKompetensi(page = 1){
            let query = this.$root.search;
            if(this.$root.search != ''){
                this.$Progress.start();
                this.searching= true;
                axios.get('api/kompetensi/skpi/'+ this.$route.params.id +'/find?q=' + query + '&page='+ page)
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
        previewKemampuan(data){
            this.kompetensiSelect = data;
            $('#previewKemampuan').modal('show');
        },
        previewBukti(data){
            this.kompetensiSelect = data;
            $('#previewBukti').modal('show');
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

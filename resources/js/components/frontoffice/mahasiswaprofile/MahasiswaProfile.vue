<template>
    <div class="container mt-4">
        <div v-if="this.$gate.isMahasiswa()">        
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <!-- general form elements -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user-graduate"></i>
                                Profil Mahasiswa
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form @submit.prevent="mahasiswa.id ? updateMahasiswa() : createMahasiswa()">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="inputNama">Nama Lengkap</label>
                                    <input v-model="form.nama" type="text" name="nama"
                                        class="form-control" placeholder="Nama Lengkap" :class="{ 'is-invalid': form.errors.has('nama') }" id="inputNama">
                                    <has-error :form="form" field="nama"></has-error>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputKotaLahir">Kota Kelahiran</label><br>
                                            <input v-model="form.kota_lahir" type="text" name="kota_lahir"
                                            class="form-control" placeholder="Kota Kelahiran" :class="{ 'is-invalid': form.errors.has('kota_lahir') }" id="inputKotaLahir">
                                            <has-error :form="form" field="kota_lahir"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                            <label for="inputTglLahir">Tanggal Kelahiran</label><br>
                                            <date-picker v-model="form.tgl_lahir" lang="en" value-type="format" name="tgl_lahir" id="inputTglLahir" class="form-control-file" 
                                            :class="{ 'is-invalid': form.errors.has('tgl_lahir') }"></date-picker>
                                            <has-error :form="form" field="tgl_lahir"></has-error>
                                        </div>  
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputNoIjazah">No Ijazah</label>
                                    <input v-model="form.no_ijazah" type="text" name="no_ijazah"
                                        class="form-control" placeholder="No Ijazah" :class="{ 'is-invalid': form.errors.has('no_ijazah') }" id="inputNoIjazah">
                                    <has-error :form="form" field="no_ijazah"></has-error>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">        
                                        <div class="form-group">
                                            <label for="inputNpm">Nomor Pokok Mahasiswa</label>
                                            <input v-model="form.npm" type="text" name="npm"
                                            class="form-control" placeholder="Nomor Pokok Mahasiswa" :class="{ 'is-invalid': form.errors.has('npm') }" id="inputNpm">
                                            <has-error :form="form" field="npm"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputIpk">Indeks Prestasi Kumulatif</label>
                                            <input v-model="form.ipk" type="text" name="ipk"
                                            class="form-control" placeholder="Indeks Prestasi Kumulatif" :class="{ 'is-invalid': form.errors.has('ipk') }" id="inputIpk">
                                            <has-error :form="form" field="ipk"></has-error>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputJurusan">Jurusan</label>
                                    <select name="id_jurusan" v-model="form.id_jurusan" id="inputJurusan" class="form-control" :class="{
                                        'is-invalid': form.errors.has('id_jurusan')}">
                                        <option value="" selected>Choose Jurusan</option>
                                        <option v-for="data in jurusan.data" :key="data.id" v-bind:value="data.id">{{ data.nama_jurusan }}</option>
                                    </select>
                                    <has-error :form="form" field="id_jurusan"></has-error>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputTglMasuk">Tanggal Mulai Studi</label><br>
                                            <date-picker v-model="form.tgl_masuk" lang="en"  name="tgl_masuk" value-type="format" id="inputTglMasuk" class="form-control-file" 
                                            :class="{ 'is-invalid': form.errors.has('tgl_masuk') }"></date-picker>
                                            <has-error :form="form" field="tgl_masuk"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputTglLulus">Tanggal Kelulusan</label><br>
                                            <date-picker v-model="form.tgl_lulus" lang="en"  name="tgl_lulus" value-type="format" id="inputTglLulus" class="form-control-file" 
                                            :class="{ 'is-invalid': form.errors.has('tgl_lulus') }"></date-picker>
                                            <has-error :form="form" field="tgl_lulus"></has-error>
                                        </div>
                                    </div>
                                </div>    
                            </div> 
                            <div class="card-footer text-center">
                                <button type="submit" class="btn btn-primary">Simpan Data</button>
                            </div>
                        </form>         
                    </div>
                    <!-- /.card -->
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
        data() {
            return {
              mahasiswa:{},
              jurusan: {},
              status: 'unregister',
              form: new Form({
              id:'',
              id_jurusan:'',
              npm:'',
              nama:'',
              kota_lahir:'',
              tgl_lahir:'',
              no_ijazah:'',
              tgl_masuk:'',
              tgl_lulus:'',
              ipk:''
            })
            }
        },
        methods: {
           getJurusan(){
                if(this.$gate.isMahasiswa()){
                    this.$Progress.start();
                    axios.get('api/jurusan/select')
                    .then(response => {
                        this.jurusan = response.data;
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        this.$Progress.fail();
                        toast.fire({
                            type: 'error',
                            title: 'Load data jurusan failed'
                        });
                    });
                }
            },
            getMahasiswa(){
                if(this.$gate.isMahasiswa()){
                    axios.get('api/mahasiswa/profile')
                    .then(response => {
                        this.mahasiswa = response.data;
                        this.form.fill(this.mahasiswa);
                    })
                    .catch(() => {
                        this.mahasiswa = false;
                    });
                }
            },
            createMahasiswa(){
                this.$Progress.start();
                this.form.post('api/mahasiswa')
                .then(() => {
                    cusEvent.$emit('ReloadData');
                    toast.fire({
                        type: 'success',
                        title: 'Mahasiswa profile has updated'
                    });
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                    toast.fire({
                        type: 'error',
                        title: 'Mahasiswa profile update failed'
                    });
                });
            },
            updateMahasiswa(){
                this.$Progress.start();
                this.form.put('api/mahasiswa/'+this.form.id)
                .then(() => {
                    cusEvent.$emit('ReloadData');
                    toast.fire({
                        type: 'success',
                        title: 'Mahasiswa profile has updated'
                    });
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                    toast.fire({
                        type: 'error',
                        title: 'Mahasiswa profile update failed'
                    });
                });
            },
            getStatus(){
                if(this.$gate.isMahasiswa()){
                    axios.get('api/skpi/cek')
                    .then(response => {
                        this.status = response.data.status;
                        if(this.status != 'unregister'){
                            this.getMahasiswa();
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
        created() {
           this.$parent.search = '';
           this.getStatus();
           this.getJurusan();
           cusEvent.$on('ReloadData', this.getMahasiswa, this.getJurusan); 
        },
        beforeDestroy(){
            cusEvent.$off('ReloadData', this.getMahasiswa, this.getJurusan);
        }
    }
</script>

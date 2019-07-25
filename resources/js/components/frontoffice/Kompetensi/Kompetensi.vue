<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<template>
    <div class="container">
      <div v-if="this.$gate.isMahasiswa()">
        <div class="row mt-4">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li @click="changeTab('data-kompetensi')" class="nav-item"><a class="nav-link  active show" href="#kompetensiTab" data-toggle="tab">Kompetensi Tambahan</a></li>
                  <li @click="changeTab('bukti-kompetensi')" class="nav-item"><a class="nav-link" href="#kompetensiWajibTab" data-toggle="tab">Kompetensi Wajib</a></li>
                </ul>
              </div>
              <div class="tab-content">
                <div id="kompetensiTab" class="tab-pane active show">  
                  <div v-if="tabActive == 'data-kompetensi'">
                    <data-kompetensi></data-kompetensi>
                  </div>
                </div>
                <div id="kompetensiWajibTab" class="tab-pane">
                  <div v-if="tabActive == 'bukti-kompetensi'">
                    <bukti-kompetensi></bukti-kompetensi>
                  </div>
                </div>    
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
          return {
            tabActive: 'data-kompetensi'
          }
        },
        methods:{
          changeTab(active){
            this.tabActive = active
          }
        },
    }
</script>

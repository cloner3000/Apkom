export default class Gate {
    
    constructor(user){
        this.user = user;
    }

    isWarek(){
        return this.user.role === 'Wakil Rektor';
    }

    isKaprodi(){
        return this.user.role === 'Kaprodi';
    }

    isAkademik(){
        return this.user.role === 'Bagian Akademik';
    }

    isMahasiswa(){
        return this.user.role === 'Mahasiswa';
    }

}
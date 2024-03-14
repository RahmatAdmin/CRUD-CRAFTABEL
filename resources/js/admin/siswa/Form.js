import AppForm from '../app-components/Form/AppForm';

Vue.component('siswa-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                email:  '' ,
                birthdate:  '' ,
                
            }
        }
    }

});
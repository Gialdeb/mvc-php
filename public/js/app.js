/**
 *  Aggiungo jQuery Validation method per validare la pwd
 *
 *  Per essere valida la password deve esserci almeno una lettera e un numero
 */


$.validator.addMethod('validPassword',
    function (value, element, param) {
        if(value != ''){
            if(value.match(/.*[a-z]+.*/i) == null){
                return false;
            }
            if(value.match(/.*\d+.*/) == null){
                return false;
            }
        }
        return true;
    },
    'Deve contenere almeno una lettera ed un numero'
);

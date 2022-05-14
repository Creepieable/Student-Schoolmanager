var errMsg, err;

$( document ).ready(function() {
    errMsg = $('#errMsg');
    errMsg.hide();

    $(document).on('click','#btn-reg', function () {
        err = 0;
        let name = $('#inp-name').val();
        let email = $('#inp-mail').val();
        let pw1 = $('#inp-pw').val();
        let pw2 = $('#inp-pw-rep').val();

        //validating Pw on Client side
        let check = validatePw(pw1,pw2);
        if(check != 1){
            if(check == -1){
                errMsg.text('Passwörter sind nicht identisch!');
                errMsg.show();
                err++;
            }
            if(check == -2){
                errMsg.html('Passwort:<br>- mindestens 8 Zeichen<br>- mindesten eine Zahl, einen Groß- und Kleinbuchstabe');
                errMsg.show();
                err++;
            }
        }

        //validating Email on Client side
        if(!validateEmailRegex(email)){
            errMsg.text('Email Adresse ist Fasch.');
            errMsg.show();
            err++;
        }

        //validating Username
        if(!validateUsername(name)){
            errMsg.text('Nutzername ungültig.');
            errMsg.show();
            err++;
        }
        if(err <= 0) {
            postRegistration(name, email, pw1);
            errMsg.hide();
        }
    });
});

function validateEmailRegex(emailStr){
    const emailRegex = /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/;
    return emailRegex.test(emailStr);
}

function validatePw(pw1,pw2){
    //at least 8 characters
    //must contain at least 1 uppercase letter, 1 lowercase letter, and 1 number
    //Can contain special characters
    const pwRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/;
    if(!pwRegex.test(pw1)){
        return -2;
    }
    if(pw1 !== pw2){
        return -1;
    }
    return 1
}

function validateUsername(nameStr){
    const nameRegex = /^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{0,29}$/;
    return nameRegex.test(nameStr);
}
function postRegistration(username, email, password){
    let salt = generateString(20);
    let pwHash = hash(salt+password);

    let postJSON = {
        request:"POST",
        type:"newUser",
        data:{
            name: username,
            email: email,
            saltedPasswordHash: pwHash,
            salt: salt
        }
    }

    console.log('ajax sending:');
    console.log(postJSON);
    $.ajax({
        url: "./API/usrmngm.php",
        type: 'POST',
        headers: {"usr-mgm-type":"register"},
        data: JSON.stringify(postJSON),
        dataType: "json",
        success: function(text) {
            console.log('done');
            //console.log(text);
        },
        error: function(xhr, status, error){
            if(xhr.status === 403){
                errMsg.text('Ein Nutzer mit diesem Namen oder dieser Email existiert bereits.');
                errMsg.show();
            }
            else{
                var errorMessage = xhr.status + ': ' + xhr.statusText
                console.error('Error - ' + errorMessage);
                console.log(error);
            }
        }
    });
}

const characters ='!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~';
function generateString(length) {
    let result = ' ';
    const charactersLength = characters.length;
    for ( let i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}
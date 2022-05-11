if(Cookies.get('__scman_us_t') !== undefined){
    let token = Cookies.get('__scman_us_t');
    
    let postJSON = {
        request:"POST",
        type:"token",
        data:{
            token: token
        }
    }

    $.ajax({
        url: "./API/usrmngm.php",
        type: 'POST',
        headers: {"usr-mgm-type":"token"},
        data: JSON.stringify(postJSON),
        //dataType: "json",
        success: function(text) {
            console.log(text);
            
            if(text.data.status === "avail"){
                window.location.href = "./calendar.php";
            }
            else{
                Cookies.remove('__scman_us_t');
            }
        },
        error: function(xhr, status, error){
            var errorMessage = xhr.status + ': ' + xhr.statusText
            console.log('Error - ' + errorMessage);
            console.log(error);
        }
    });
}

$(document).on('click','#submit-btn', function () {  
    login();
});


function login(){
    let name = $('#name-inp').val();
    let pw = $('#pw-inp').val();

    let postJSON = {
        request:"POST",
        type:"salt",
        data:{
            name: name
        }
    }

    $.ajax({
        url: "./API/usrmngm.php",
        type: 'POST',
        headers: {"usr-mgm-type":"salt"},
        data: JSON.stringify(postJSON),
        dataType: "json",
        success: function(text) {
            let salt = text.data.salt;

            var md = forge.md.sha256.create();  
            md.start();  
            md.update(salt+pw, "utf8");  
            let saltedPw = md.digest().toHex();

            let postJSON = {
                request:"GET",
                type:"login",
                data:{
                    user: name,
                    password: saltedPw
                }
            }

            $.ajax({
                url: "./API/usrmngm.php",
                type: 'POST',
                headers: {"usr-mgm-type":"login"},
                data: JSON.stringify(postJSON),
                dataType: "json",
                success: function(text) {
                    let token = text.data.token;

                    if($('#keep-check').prop('checked')){
                        Cookies.set('__scman_us_t', token, { expires: 60 });
                        window.location.href = "./calendar.php";
                    }
                    else{
                        Cookies.set('__scman_us_t', token);
                        window.location.href = "./calendar.php";
                    }        
                },
                error: function(xhr, status, error){
                    var errorMessage = xhr.status + ': ' + xhr.statusText
                    console.log('Error - ' + errorMessage);
                    console.log(error);
                }
            });
        },
        error: function(xhr, status, error){
            var errorMessage = xhr.status + ': ' + xhr.statusText
            console.log('Error - ' + errorMessage);
            console.log(error);
        }
    });
}
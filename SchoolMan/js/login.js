//check token availability and redirect
if(validateCurrentToken()){
    window.location.href = "./calendar.php";
}
else{
    Cookies.remove('__scman_us_t');
}

$(document).on('click','#submit-btn', function () {  
    login();
});
$('.loginTB').on("keypress", function(e){
    if(e.which == 13){
        $("body").append("<p>You've pressed the enter key!</p>");
    }
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
            console.log(text);
            let salt = text.data.salt;

            let saltedPw = hash(salt+pw)
            
            let postJSON = {
                request:"GET",
                type:"login",
                data:{
                    name: name,
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
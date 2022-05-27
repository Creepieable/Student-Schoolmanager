const userToken = Cookies.get('__scman_us_t');

$(document).on('click','#logout-btn', function () {
    let token = Cookies.get('__scman_us_t');
    $.ajax({
        url: "./API/usrmngm.php",
        type: 'DELETE',
        headers: {"usr-mgm-type":"logout",
                  "usr-token":token},
        success: function() {
            Cookies.remove('__scman_us_t');
            window.location.href = "./index.html";
        },
        error: function(xhr, status, error){
            var errorMessage = xhr.status + ': ' + xhr.statusText
            console.log('Error - ' + errorMessage);
            console.log(error);
            console.log(xhr.responseText);
        }
    });
});

function validateCurrentToken(token){
    if(Cookies.get('__scman_us_t') !== undefined){        
        let postJSON = {
            request:"POST",
            type:"tokenAvail",
            data:{
                token: token
            }
        }

        let status;
        $.ajax({
            url: "./API/usrmngm.php",
            type: 'POST',
            async: false,
            headers: {"usr-mgm-type":"token"},
            data: JSON.stringify(postJSON),
            dataType: "json",
            success: function(text) {
                status = text.status;
            },
            error: function(xhr, status, error){
                if(!(xhr.status === 404 || xhr.status === 410)){
                    var errorMessage = xhr.status + ': ' + xhr.statusText
                    console.log('Error - ' + errorMessage);
                    console.log(error); 
                }
                status = "unavail";
            }
        });

        if(status === "avail") return true;
        else return false;

    }
    else{
        return false;
    }
}

//sha256 hashfunction
function hash(inp)
{
    var md = forge.md.sha256.create();  
    md.start();  
    md.update(inp, "utf8");  
    var hashText = md.digest().toHex();  
    return hashText;
} 
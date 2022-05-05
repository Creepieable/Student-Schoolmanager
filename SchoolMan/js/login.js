if(Cookies.get('__scman_us_t') !== undefined){
    window.location.href = "./calendar.php";
}

$(document).on('click','#submit-btn', function () {  
    if($('#keep-check').prop('checked')){
        Cookies.set('__scman_us_t', 'gsdzufzasdfzufnklsdnkdklasdklgnasdfgnjasdfn', { expires: 60 });
    }
    else{
        Cookies.set('__scman_us_t', 'gsdzufzasdfzufnklsdnkdklasdklgnasdfgnjasdfn');
    }
    window.location.href = "./calendar.php";
});
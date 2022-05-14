//check token availability and redirect
if(validateCurrentToken()){
    window.location.href = "./calendar.php";
}
else{
    Cookies.remove('__scman_us_t');
}